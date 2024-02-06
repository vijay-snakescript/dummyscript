<?php

namespace App\View\Components;

use App\Helpers\Facads\Menu;
use Illuminate\View\Component;
use App\Http\View\Composer\AdminMenuComposer;

class ApplicationSidebar extends Component
{
    /**
     * The menu collection.
     *
     * @var collection
     */
    public $menu;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        app(AdminMenuComposer::class)->register();

        $this->menu = Menu::adminMenus();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.application-sidebar');
    }
}
