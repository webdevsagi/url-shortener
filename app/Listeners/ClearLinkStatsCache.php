<?php

namespace App\Listeners;

use App\Events\LinkHitRecorded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ClearLinkStatsCache
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(LinkHitRecorded $event): void
    {
        //
    }
}
