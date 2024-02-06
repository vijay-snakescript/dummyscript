<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (DB::table('users')->count() == 0) {
            $users = [
                ['role' => ['name' => 'Super Admin', 'team_id' => null], 'user' => ['name' => 'Administrator', 'username' => 'admin', 'about' => 'Administrator', 'email' => 'admin@example.com', 'picture' => 'default.png', 'status' => '1', 'email_verified_at' => now(), 'password' => Hash::make('admin')]],
                // ['role' => ['name' => 'User', 'team_id' => null], 'user' => ['name' => 'Public User', 'username' => 'joe', 'about' => 'Public User', 'email' => 'joe@example.com', 'picture' => 'default.png', 'status' => '1', 'email_verified_at' => now(), 'password' => Hash::make('joe')]],
            ];

            //find super admin role.
            foreach ($users as $userData) {
                $user = User::create($userData['user']);

                if ($userData['role']) {
                    $user->assignRole($userData['role']);
                }
            }
        }
    }
}
