<?php

namespace App\Helpers\Facads;

use Illuminate\Support\Facades\Facade;

class UniqueSlugFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'UniqueSlug';
    }
}
