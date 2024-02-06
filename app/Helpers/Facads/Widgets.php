<?php

namespace App\Helpers\Facads;

use Illuminate\Support\Facades\Facade;

class Widgets extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ArtisanWidget';
    }
}
