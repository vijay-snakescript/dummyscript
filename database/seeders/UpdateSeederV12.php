<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class UpdateSeederV12 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $tools = [
            [
                'category' => 'website-management-tools',
                'tools' => [
                    [
                        'display' => 65,
                        'slug' => "xml-sitemap-generator",
                        'class_name' => 'App\Tools\SitemapGenerator',
                        'icon_type' => 'class',
                        'icon_class' => 'json-to-xml',
                        'en' => ['name' => 'XML Sitemap Generator', 'description' => 'Effortlessly create an XML sitemap for your website with our free generator. Notify search engines about your web pages and changes, and ensure accurate indexing. Get started now!', 'content' => 'Effortlessly create an XML sitemap for your website with our free generator. Notify search engines about your web pages and changes, and ensure accurate indexing. Get started now!']
                    ],
                ]
            ],
        ];

        foreach ($tools as $data) {
            $category = Category::slug($data['category'])->first();
            if (!$category) {
                continue;
            }

            foreach ($data['tools'] as $item) {
                $tool = $category->tools()->slug($item['slug'])->firstOr(function () use ($category, $item) {
                    return $category->tools()->make([]);
                });

                $tool->fill($item);
                $tool->save();
                $tool->category()->sync($category);
            }
        }

        $this->call(HomepageSeeder::class);
    }
}
