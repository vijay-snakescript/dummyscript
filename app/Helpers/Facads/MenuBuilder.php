<?php

namespace App\Helpers\Facads;

use Illuminate\Support\Facades\Facade;

class MenuBuilder extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'registermenu';
    }
}
