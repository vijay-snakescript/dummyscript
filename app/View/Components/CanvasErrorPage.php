<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CanvasErrorPage extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(public string $wrapClass, public bool $hasNavbar = true)
    {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('layouts.error-page');
    }
}
