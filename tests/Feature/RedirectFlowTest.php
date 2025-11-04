<?php

namespace Tests\Feature;

use App\Jobs\LogLinkHit;
use App\Models\Link;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class RedirectFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_redirects_to_target_url_when_active(): void
    {
        $link = Link::create([
            'slug' => 'test123',
            'target_url' => 'https://example.com',
            'is_active' => true,
        ]);

        $response = $this->get('/r/test123');

        $response->assertStatus(302)
            ->assertRedirect('https://example.com');
    }

    public function test_returns_404_when_slug_not_found(): void
    {
        $response = $this->get('/r/nonexistent');

        $response->assertStatus(404);
    }

    public function test_returns_410_when_link_inactive(): void
    {
        $link = Link::create([
            'slug' => 'inactive',
            'target_url' => 'https://example.com',
            'is_active' => false,
        ]);

        $response = $this->get('/r/inactive');

        $response->assertStatus(410);
    }

    public function test_dispatches_job_to_log_hit(): void
    {
        Queue::fake();

        $link = Link::create([
            'slug' => 'test123',
            'target_url' => 'https://example.com',
            'is_active' => true,
        ]);

        $response = $this->get('/r/test123');

        Queue::assertPushed(LogLinkHit::class, function ($job) use ($link) {
            return $job->linkId === $link->id;
        });
    }

    public function test_job_creates_link_hit_record(): void
    {
        $link = Link::create([
            'slug' => 'test123',
            'target_url' => 'https://example.com',
            'is_active' => true,
        ]);

        $job = new LogLinkHit($link->id, '192.168.1.1', 'Mozilla/5.0');
        $job->handle();

        $this->assertDatabaseHas('link_hits', [
            'link_id' => $link->id,
            'ip' => '192.168.1.1',
            'user_agent' => 'Mozilla/5.0',
        ]);
    }

    public function test_statistics_endpoint_returns_correct_data(): void
    {
        $link = Link::create([
            'slug' => 'test123',
            'target_url' => 'https://example.com',
            'is_active' => true,
        ]);

        // Create some hits
        for ($i = 0; $i < 3; $i++) {
            $link->hits()->create([
                'ip' => "192.168.1.{$i}",
                'user_agent' => 'Mozilla/5.0',
                'created_at' => now(),
            ]);
        }

        $response = $this->getJson("/api/links/test123/stats");

        $response->assertStatus(200)
            ->assertJson([
                'slug' => 'test123',
                'target_url' => 'https://example.com',
                'total_hits' => 3,
            ])
            ->assertJsonStructure([
                'last_hits' => [
                    '*' => ['ip', 'user_agent', 'timestamp']
                ]
            ]);
    }

    public function test_statistics_truncates_ip_addresses(): void
    {
        $link = Link::create([
            'slug' => 'test123',
            'target_url' => 'https://example.com',
            'is_active' => true,
        ]);

        $link->hits()->create([
            'ip' => '192.168.1.100',
            'user_agent' => 'Mozilla/5.0',
            'created_at' => now(),
        ]);

        $response = $this->getJson("/api/links/test123/stats");

        $response->assertStatus(200)
            ->assertJsonPath('last_hits.0.ip', '192.168.1.xxx');
    }
}
