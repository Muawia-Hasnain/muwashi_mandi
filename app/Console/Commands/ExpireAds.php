<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ExpireAds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ads:expire';
    protected $description = 'Automatically expire ads older than 30 days';

    public function handle()
    {
        // 1. Expire old ads (30 days)
        $count = \App\Models\Ad::where('status', 'approved')
            ->where('created_at', '<', now()->subDays(30))
            ->update(['status' => 'expired']);

        // 2. Remove expired Boosts
        $boosts = \App\Models\Ad::where('is_boosted', true)
            ->whereNotNull('boost_expires_at')
            ->where('boost_expires_at', '<', now())
            ->update(['is_boosted' => false, 'boost_expires_at' => null]);

        // 3. Remove expired Features
        $features = \App\Models\Ad::where('is_featured', true)
            ->whereNotNull('featured_expires_at')
            ->where('featured_expires_at', '<', now())
            ->update(['is_featured' => false, 'featured_expires_at' => null]);

        $this->info("Expired {$count} ads. Removed {$boosts} expired boosts and {$features} expired features.");
    }
}
