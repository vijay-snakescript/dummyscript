<?php

namespace Database\Seeders;

use App\Models\WidgetArea;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WidgetsAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (DB::table('widget_areas')->count() == 0) {
            $widget_areas = [
                ['name' => 'sidebar', 'title' => 'Homepage', 'description' => 'Homepage widget area.', 'order' => '1'],
                ['name' => 'tools-sidebar', 'title' => 'Tools', 'description' => 'Tools page widget area.', 'order' => '1'],
                ['name' => 'categories-sidebar', 'title' => 'Categories', 'description' => 'Category page widgets area.', 'order' => '3'],
                ['name' => 'tags-sidebar', 'title' => 'Tags', 'description' => 'Tag page widgets area.', 'order' => '4'],
                ['name' => 'pages-sidebar', 'title' => 'Page', 'description' => 'Single Page widgets area.', 'order' => '5'],
                ['name' => 'post-sidebar', 'title' => 'Blog', 'description' => 'Blog pages widgets area.', 'order' => '6'],

                // Footer area widgets
                ['name' => 'footer-1', 'title' => 'Footer Area 1', 'description' => '', 'order' => '30', 'deleted_at' => null],
                ['name' => 'footer-2', 'title' => 'Footer Area 2', 'description' => '', 'order' => '31', 'deleted_at' => null],
                ['name' => 'footer-3', 'title' => 'Footer Area 3', 'description' => '', 'order' => '32', 'deleted_at' => null],
                ['name' => 'footer-4', 'title' => 'Footer Area 4', 'description' => '', 'order' => '33', 'deleted_at' => null],
                ['name' => 'footer-5', 'title' => 'Footer Area 5', 'description' => '', 'order' => '34', 'deleted_at' => now()],
                ['name' => 'footer-6', 'title' => 'Footer Area 6', 'description' => '', 'order' => '35', 'deleted_at' => now()],
            ];

            foreach ($widget_areas as $area) {
                WidgetArea::create($area);
            }
        }
    }
}
