<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LanguagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $languages = [
            [
                'name' => 'English',
                'locale' => 'en',
                'is_default' => true,
                'status' => true,
            ]
        ];

        if (DB::table('languages')->count() == 0) {
            foreach ($languages as $language) {
                Language::create($language);
            }
        }
    }
}
