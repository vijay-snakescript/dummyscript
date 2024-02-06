<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (DB::table('pages')->count() == 0) {
            $pages = [
                [
                    'published' => true, 'en' => ['title' => 'Privacy Policy', 'slug' => 'privacy-policy', 'content' => 'Privacy policy content goes here...']
                ],
            ];
            foreach ($pages as $data) {
                $page = User::first()->pages()->create([
                    'published' => true,
                ]);

                $page->fill(['en' => $data['en']]);
                $page->save();
            }
        }
    }
}
