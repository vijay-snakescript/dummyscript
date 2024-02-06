<?php

namespace App\Helpers\Facads;

class Payment extends \Illuminate\Support\Facades\Facade
{
    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor()
    {
        return 'payment';
    }
}
