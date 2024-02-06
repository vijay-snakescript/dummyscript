<?php

namespace App\Models;

use Spatie\Permission\Guard;
use Spatie\Permission\Exceptions\PermissionAlreadyExists;
use Spatie\Permission\Models\Permission as ModelsPermission;

class Permission extends ModelsPermission
{
    public static function create(array $attributes = [])
    {
        $attributes['guard_name'] = $attributes['guard_name'] ?? Guard::getDefaultName(static::class);

        $permission = static::getPermission([
            'name' => $attributes['name'],
            'title' => $attributes['title'],
            'group' => $attributes['group'],
            'description' => $attributes['description'],
            'guard_name' => $attributes['guard_name']
        ]);

        if ($permission) {
            throw PermissionAlreadyExists::create($attributes['name'], $attributes['guard_name']);
        }

        return static::query()->create($attributes);
    }
}
