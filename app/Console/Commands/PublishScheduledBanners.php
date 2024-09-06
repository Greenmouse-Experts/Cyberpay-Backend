<?php

namespace App\Console\Commands;

use App\Models\AdsBanner;
use Carbon\Carbon;
use Illuminate\Console\Command;

class PublishScheduledBanners extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ads:publish-scheduled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish scheduled ads banners whose time has come';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $now = Carbon::now();

        // Fetch banners that are scheduled to be published
        $scheduledBanners = AdsBanner::where('is_published', false)
            ->where('scheduled_at', '<=', $now)
            ->get();

        foreach ($scheduledBanners as $banner) {
            $banner->update(['is_published' => true]);
            // Additional logic to notify or perform other actions after publishing
        }

        $this->info('Scheduled ads banners have been published.');
    }
}
