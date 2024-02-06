<?php

namespace App\Models;

use Spatie\Permission\Models\Role as ModelsRole;

class Role extends ModelsRole
{
    protected $guard_name = 'web';

    protected $fillable = ['name', 'default', 'guest', 'description'];
}
