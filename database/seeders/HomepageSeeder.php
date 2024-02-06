<?php

namespace Database\Seeders;

use App\Models\Tool;
use Illuminate\Database\Seeder;

class HomepageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tools = Tool::all();
        foreach ($tools as $tool) {
            $instance = new $tool->class_name();
            if (method_exists($instance, 'indexContent')) {
                $tool->update(['en' => ['index_content' => $instance->indexContent()]]);
            }
        }
    }
}
