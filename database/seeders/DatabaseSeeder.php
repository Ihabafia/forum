<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(TopicSeeder::class);

        $topics = Topic::all();

        $user = User::factory()->create([
            'name' => 'Ihab Abou Afia',
            'email' => 'ihab@abouafia.com',
        ]);
        $users = User::factory(10)->create();

        $users = User::all();

        $posts = Post::factory(200)
            ->withFixture()
            ->has(Comment::factory($this->random())->recycle($users))
            ->recycle([$users, $topics])
            ->create();
    }

    private function random(): int
    {
        return rand(20, 30);
    }
}
