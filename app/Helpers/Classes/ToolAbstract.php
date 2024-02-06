<?php

namespace App\Helpers\Classes;

use App\Contracts\ToolInterface;

abstract class ToolAbstract implements ToolInterface
{
    public $name = '';

    public function __construct()
    {
    }
}
