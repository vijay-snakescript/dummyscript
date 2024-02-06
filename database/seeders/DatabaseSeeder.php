<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(LanguagesSeeder::class);
        $this->call(SettingsTableSeeder::class);
        $this->call(RolePermissionsSeeder::class);
        $this->call(UsersSeeder::class);
        $this->call(WidgetsAreaSeeder::class);
        $this->call(PagesSeeder::class);
        $this->call(ToolSeeder::class);
        $this->call(PropertiesSeeder::class);
        $this->call(MenuTableSeeder::class);
        $this->call(PlansSeeder::class);
        $this->call(FaqsSeeder::class);
        $this->call(PostsSeeder::class);
        $this->call(ToolDriversSeeder::class);
        $this->call(HomepageSeeder::class);
    }
}
