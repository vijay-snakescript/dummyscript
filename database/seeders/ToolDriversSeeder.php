<?php

namespace Database\Seeders;

use App\Models\Tool;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ToolDriversSeeder extends Seeder
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
            $form_fields = [];
            if (method_exists($instance, 'getFileds') && empty($tool->settings)) {
                $form_fields = $instance->getFileds();
                $data = [];
                $data['settings']  = $form_fields['default'];
                $tool->update($data);
            }
        }
    }
}
