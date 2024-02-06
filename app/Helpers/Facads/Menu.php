<?php

namespace App\Helpers\Facads;

use Illuminate\Support\Facades\Facade;

class Menu extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'menumanager';
    }
}
