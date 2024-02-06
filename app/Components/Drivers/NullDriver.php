<?php

namespace App\Components\Drivers;

use Exception;
use App\Contracts\ToolDriverInterface;

class NullDriver implements ToolDriverInterface
{
    public function parse($request)
    {
        throw new Exception(__("common.toolDriverNotAvailable"));
    }
}
