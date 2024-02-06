<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (DB::table('categories')->count() == 0) {
            $categories = [
                ['name' => 'Uncategorized', 'slug' => 'uncategorized', 'type' => 'post']
            ];

            foreach ($categories as $category) {
                Category::create($category);
            }
        }

        if (DB::table('posts')->count() == 0) {
            $posts = [
                ['status' => 'published', 'comments_status' => true, 'title' => 'Hello world!', 'slug' => 'hello-world', 'contents' => 'Welcome to MonsterTools. This is your first post. Edit or delete it, then start blogging!', 'meta_title' => 'Hello world!', 'og_title' => 'Hello world!']
            ];

            $user = User::first();
            foreach ($posts as $data) {
                $post = $user->posts()->create($data);
                $categories = Category::first()->pluck('id');
                $post->categories()->sync($categories);
            }
        }
    }
}
