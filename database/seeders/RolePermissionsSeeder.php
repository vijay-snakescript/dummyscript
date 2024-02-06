<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->roles();
        $this->permissions();
    }

    protected function permissions()
    {
        $permissions = collect([
            ['name' => 'manage users', 'group' => 'users', 'description' => 'Manage System Users.'],
            ['name' => 'create users', 'group' => 'users', 'description' => 'Create System Users.'],
            ['name' => 'edit users', 'group' => 'users', 'description' => 'Edit System Users.'],
            ['name' => 'manage profile', 'group' => 'users', 'description' => 'Manage account profile.'],
            ['name' => 'manage roles', 'group' => 'roles', 'description' => 'Manage System User Roles.'],
            ['name' => 'create roles', 'group' => 'roles', 'description' => 'Create Roles.'],
            ['name' => 'edit roles', 'group' => 'roles', 'description' => 'Edit Roles.'],
            ['name' => 'delete roles', 'group' => 'roles', 'description' => 'Delete Roles.'],
            ['name' => 'manage permissions', 'group' => 'roles', 'description' => 'Manage System User Permissions.'],
            ['name' => 'manage dashboard', 'group' => 'page', 'description' => 'Manage dashboard.'],
            ['name' => 'application operations', 'group' => 'system', 'description' => 'Manage application operations.'],
            ['name' => 'manage transactions', 'group' => 'system', 'description' => 'Manage System transactions.'],
            ['name' => 'manage updates', 'group' => 'system', 'description' => 'Manage System updates.'],
            ['name' => 'manage page', 'group' => 'page', 'description' => 'Manage Page.'],
            ['name' => 'create page', 'group' => 'page', 'description' => 'Create Page.'],
            ['name' => 'edit page', 'group' => 'page', 'description' => 'Edit Page.'],
            ['name' => 'delete page', 'group' => 'page', 'description' => 'Delete Page.'],
            ['name' => 'manage post', 'group' => 'page', 'description' => 'Manage Post.'],
            ['name' => 'create post', 'group' => 'page', 'description' => 'Create Post.'],
            ['name' => 'edit post', 'group' => 'page', 'description' => 'Edit Post.'],
            ['name' => 'delete post', 'group' => 'page', 'description' => 'Delete Post.'],
            ['name' => 'view category', 'group' => 'page', 'description' => 'View Category.'],
            ['name' => 'create category', 'group' => 'page', 'description' => 'Create Category.'],
            ['name' => 'edit category', 'group' => 'page', 'description' => 'Edit Category.'],
            ['name' => 'delete category', 'group' => 'page', 'description' => 'Delete Category.'],
            ['name' => 'view tag', 'group' => 'page', 'description' => 'View tag.'],
            ['name' => 'create tag', 'group' => 'page', 'description' => 'Create tag.'],
            ['name' => 'edit tag', 'group' => 'page', 'description' => 'Edit tag.'],
            ['name' => 'delete tag', 'group' => 'page', 'description' => 'Delete tag.'],
            ['name' => 'manage menus', 'group' => 'menus', 'description' => 'Manage Menus.'],
            ['name' => 'create menus', 'group' => 'menus', 'description' => 'Create Menus.'],
            ['name' => 'edit menus', 'group' => 'menus', 'description' => 'Edit Menus.'],
            ['name' => 'delete menus', 'group' => 'menus', 'description' => 'Delete Menus.'],
            ['name' => 'manage settings', 'group' => 'system', 'description' => 'Delete Settings.'],
            ['name' => 'manage tools', 'group' => 'tool', 'description' => 'Manage Tool.'],
            ['name' => 'edit tools', 'group' => 'tool', 'description' => 'Edit Tool.'],
            ['name' => 'manage plans', 'group' => 'plan', 'description' => 'Manage plans.'],
            ['name' => 'create plans', 'group' => 'plan', 'description' => 'Create plans.'],
            ['name' => 'edit plans', 'group' => 'plan', 'description' => 'Edit plans.'],
            ['name' => 'delete plans', 'group' => 'plan', 'description' => 'Delete plans.'],
            ['name' => 'manage advertisements', 'group' => 'plan', 'description' => 'Manage advertisements.'],
            ['name' => 'create advertisements', 'group' => 'plan', 'description' => 'Create advertisements.'],
            ['name' => 'edit advertisements', 'group' => 'plan', 'description' => 'Edit advertisements.'],
            ['name' => 'delete advertisements', 'group' => 'plan', 'description' => 'Delete advertisements.'],
        ]);

        $permissions->each(function ($item) {
            $item['title'] = ucfirst($item['name']);

            $permission = Permission::firstOrCreate(['name' => $item['name']]);
            $permission->update($item);
        });
    }

    protected function roles()
    {
        if (DB::table('roles')->count() == 0) {
            $roles = [
                ['name' => 'Super Admin'],
                ['name' => 'User'],
            ];

            foreach ($roles as $role) {
                Role::create($role);
            }
        }
    }
}
