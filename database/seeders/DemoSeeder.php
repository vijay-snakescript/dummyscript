<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\Post;
use App\Models\Tool;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Database\Factories\ToolFactory;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (class_exists('Faker\Factory')) {
            User::factory(10)->create();

            if (DB::table('tags')->count() == 0) {
                Tag::factory(10)->create();
            }

            if (DB::table('categories')->count() == 0) {
                Category::factory(10)->create();
            }

            if (DB::table('posts')->count() == 0) {
                Post::factory()->count(2)->featured()->create();
                Post::factory()->count(1)->editorchoice()->create();
                Post::factory()->count(10)->create();
            }

            $tools = Tool::all();
            foreach ($tools as $tool) {
                $details = new ToolFactory();
                $data = $details->definition();
                $data['og_title'] = $tool->name;
                $data['meta_title'] = $tool->name;

                $tool->update($data);
            }
        }
    }
}
