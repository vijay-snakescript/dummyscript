<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class Update11Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ToolDriversSeeder::class);
        $this->call(HomepageSeeder::class);
        $this->call(RolePermissionsSeeder::class);
    }
}
