<?php

namespace Tests\Feature;

use App\Models\Link;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LinkCreationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['app.api_key' => 'test-api-key']);
    }

    public function test_can_create_link_with_valid_data(): void
    {
        $response = $this->postJson('/api/links', [
            'target_url' => 'https://example.com',
        ], [
            'X-Api-Key' => 'test-api-key',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'slug',
                'target_url',
                'short_url',
                'is_active',
                'created_at',
            ]);

        $this->assertDatabaseHas('links', [
            'target_url' => 'https://example.com',
            'is_active' => true,
        ]);
    }

    public function test_can_create_link_with_custom_slug(): void
    {
        $response = $this->postJson('/api/links', [
            'target_url' => 'https://example.com',
            'slug' => 'custom123',
        ], [
            'X-Api-Key' => 'test-api-key',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'slug' => 'custom123',
            ]);

        $this->assertDatabaseHas('links', [
            'slug' => 'custom123',
            'target_url' => 'https://example.com',
        ]);
    }

    public function test_fails_without_api_key(): void
    {
        $response = $this->postJson('/api/links', [
            'target_url' => 'https://example.com',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'error' => 'Unauthorized',
            ]);
    }

    public function test_fails_with_invalid_api_key(): void
    {
        $response = $this->postJson('/api/links', [
            'target_url' => 'https://example.com',
        ], [
            'X-Api-Key' => 'wrong-key',
        ]);

        $response->assertStatus(401);
    }

    public function test_fails_with_invalid_url(): void
    {
        $response = $this->postJson('/api/links', [
            'target_url' => 'not-a-valid-url',
        ], [
            'X-Api-Key' => 'test-api-key',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['target_url']);
    }

    public function test_fails_with_duplicate_slug(): void
    {
        Link::create([
            'slug' => 'existing',
            'target_url' => 'https://example.com',
        ]);

        $response = $this->postJson('/api/links', [
            'target_url' => 'https://another.com',
            'slug' => 'existing',
        ], [
            'X-Api-Key' => 'test-api-key',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['slug']);
    }

    public function test_rate_limiting_works(): void
    {
        // Make 31 requests (rate limit is 30/min)
        for ($i = 0; $i < 31; $i++) {
            $response = $this->postJson('/api/links', [
                'target_url' => 'https://example.com',
            ], [
                'X-Api-Key' => 'test-api-key',
            ]);

            if ($i < 30) {
                $response->assertStatus(201);
            } else {
                $response->assertStatus(429); // Too Many Requests
            }
        }
    }
}
