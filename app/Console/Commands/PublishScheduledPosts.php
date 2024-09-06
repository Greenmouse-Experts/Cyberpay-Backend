<?php

namespace App\Console\Commands;

use App\Models\BlogPost;
use Carbon\Carbon;
use Illuminate\Console\Command;

class PublishScheduledPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:publish-scheduled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish blog posts that are scheduled for publication';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $now = Carbon::now();

        // Fetch posts scheduled to be published and are not yet published
        $posts = BlogPost::where('is_published', false)
                         ->where('scheduled_at', '<=', $now)
                         ->get();

        foreach ($posts as $post) {
            $post->update(['is_published' => true]);
            $this->info("Published post: {$post->title}");
        }

        $this->info('Scheduled posts have been processed.');
    }
}
