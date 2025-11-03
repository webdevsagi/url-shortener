<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

use App\Models\LinkHit;

class LogLinkHit implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $linkId,
        public string $ip,
        public ?string $userAgent
    ) {}

public
function handle(): void
{
    LinkHit::create([
        "link_id" => $this->linkId,
        "ip" => $this->ip,
        "user_agent" => $this->userAgent,
        "created_at" => now(),
    ]);
}
}
