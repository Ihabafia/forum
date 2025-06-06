<?php

namespace Database\Seeders;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\LazyCollection;

use function Laravel\Prompts\progress;

class LikeLoadTestSeeder extends Seeder
{
    public function run(): void
    {
        $post = Post::find(1);
        $progress = progress(label: 'Creating likes', steps: 500_000);

        $progress->start();

        LazyCollection::times(5_00)->each(function () use ($post, $progress) {
            Like::factory(1000)->for($post, 'likeable')->create();
            $progress->advance(1000);
        });

        $progress->finish();

        $post->increment('likes_count', 500_000);
    }
}
