<?php

namespace Database\Seeders;

use App\Models\Property;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PropertiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $properties = [
            [
                'status' => true,
                'type' => "1",
                'default_value' => 10,
                'is_guest' => 1,
                'field_type' => "property-textfield",
                'prop_key' => "du-tool",
                'en' => ['name' => 'Daily Usage', 'description' => 'Daily usage restrictions refer to the limitations placed on the number of requests that a user can perform within a 24-hour period.']
            ],
            [
                'status' => true,
                'type' => "1",
                'default_value' => 100,
                'is_guest' => 1,
                'field_type' => "property-textfield",
                'prop_key' => "wc-tool",
                'en' => ['name' => 'Word Count', 'description' => 'Word count limitation refers to the maximum number of words allowed in a text, such as in a article rewrite or form field.']
            ],
            [
                'status' => true,
                'type' => "1",
                'default_value' => 10,
                'is_guest' => 1,
                'field_type' => "property-textfield",
                'prop_key' => "fs-tool",
                'en' => ['name' => 'File Size', 'description' => 'File size limitation refers to the maximum size of a file that can be uploaded in tools, the size is in megabytes.']
            ],
            [
                'status' => true,
                'type' => "1",
                'default_value' => 10,
                'is_guest' => 1,
                'field_type' => "property-textfield",
                'prop_key' => "no-file-tool",
                'en' => ['name' => 'No of Image', 'description' => 'Image limitation refers to the maximum number of images that can be uploaded on supported tools.']
            ],
            [
                'status' => true,
                'type' => "1",
                'default_value' => 10,
                'is_guest' => 1,
                'field_type' => "property-textfield",
                'prop_key' => "no-domain-tool",
                'en' => ['name' => 'No of Domain', 'description' => 'Domain limitation refers to the maximum number of domains that can be processed in single request.']
            ],
            // [
            //     'status' => true,
            //     'type' => "1",
            //     'default_value' => 1,
            //     'is_guest' => 0,
            //     'field_type' => "property-checkbox",
            //     'prop_key' => "wm-tool",
            //     'en' => ['name' => 'Watermark', 'description' => 'Watermark on the output display.']
            // ],
        ];

        foreach ($properties as $data) {
            $property_check = Property::where('prop_key', $data['prop_key'])->first();
            if (!isset($property_check->id)) {
                $property = Property::create(
                    [
                        'status' => $data['status'],
                        'type' => $data['type'],
                        'value' => $data['default_value'],
                        'field_type' => $data['field_type'],
                        'prop_key' => $data['prop_key'],
                    ]
                );

                $property->fill(['en' => $data['en']]);
                $property->save();
            }
        }
    }
}
