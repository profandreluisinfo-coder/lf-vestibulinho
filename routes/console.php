<?php

use App\Models\Post;
use Illuminate\Support\Facades\Schedule;

Schedule::call(function (): void {
    $now = now();

    Post::query()
        ->where('published', false)
        ->whereNotNull('scheduled_at')
        ->where('scheduled_at', '<=', $now)
        ->orderBy('id')
        ->chunkById(100, function ($posts) use ($now): void {
            foreach ($posts as $post) {
                $scheduledAt = $post->scheduled_at;

                Post::query()
                    ->whereKey($post->id)
                    ->where('published', false)
                    ->where('scheduled_at', '<=', $now)
                    ->update([
                        'published'    => true,
                        'published_at' => $scheduledAt->toDateString(),
                        'scheduled_at' => null,
                        'updated_at'   => $now,
                    ]);
            }
        });
})->everyMinute()->name('publish-scheduled-posts')->withoutOverlapping();
