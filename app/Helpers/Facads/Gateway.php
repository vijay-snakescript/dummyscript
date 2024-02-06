<?php

namespace App\Helpers\Facads;

class Gateway extends \Illuminate\Support\Facades\Facade
{
    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor()
    {
        return 'gateway';
    }
}
