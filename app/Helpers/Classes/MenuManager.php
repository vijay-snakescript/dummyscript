<?php

namespace App\Helpers\Classes;

use App\Helpers\Classes\MenuItems;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class MenuManager
{
    /**
     * Front Menu Collection.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $collection;

    /**
     * Construct for the menu builder.
     */
    public function __construct()
    {
        $this->collection = Collection::make([]);
    }

    /**
     * Make a Front End Menu an Object.
     *
     * @param  string   $key
     * @param  callable $callable
     * @return self
     */
    public function make($key, callable $callable, $order = null)
    {
        $priority = $order === null ? $this->collection->count() : $order;
        $menu = new MenuItems($callable);
        $menu->key($key);
        $menu->priority($priority);
        $this->collection->put($key, $menu);

        if (Auth::check()) {
            Cache::forget('admin_menu_builder_' . Auth::id());
        }

        return $this;
    }

    /**
     * Return Menu Object.
     *
     * @var    string
     * @return \AvoRed\Framework\Menu\Menu
     */
    public function get($key)
    {
        return $this->collection->get($key);
    }

    /**
     * Return all available Menu in Menu.
     *
     * @param  void
     * @return \Illuminate\Support\Collection
     */
    public function all($admin = true)
    {
        if ($admin) {
            return $this->collection->filter(
                function ($item) {
                    return $item->type() === MenuItems::ADMIN;
                }
            );
        } else {
            return $this->collection->filter(
                function ($item) {
                    return $item->type() === MenuItems::FRONT;
                }
            );
        }
    }

    public function getMenuItemFromRouteName($name)
    {
        $currentOpenKey = '';
        $currentMenuItemsKey = '';
        foreach ($this->collection as $key => $menuGroup) {
            if ($menuGroup->hasSubMenu()) {
                $subMenus = $menuGroup->subMenu($key);

                foreach ($subMenus as $subKey => $subMenu) {
                    if ($subMenu->route() == $name) {
                        $currentOpenKey = $key;
                        $currentMenuItemsKey = $subMenu->key();
                    }
                }
            }
        }

        return [$currentOpenKey, $currentMenuItemsKey];
    }

    /**
     * Return all available Menu in Menu.
     *
     * @param  void
     * @return \Illuminate\Support\Collection
     */
    public function frontMenus()
    {
        $frontMenus = collect();

        $i = 1;
        foreach ($this->collection as $item) {
            if ($item->type() === MenuItems::FRONT) {
                $menu = new \stdClass;
                $menu->id = $i;
                $menu->name = $item->label;
                $menu->route = $item->route();
                $menu->params = $item->params();
                $menu->url = route($item->route(), $item->params());
                $menu->submenus = $item->submenus ?? [];
                $frontMenus->push($menu);
                $i++;
            }
        }

        return $frontMenus;
    }

    /**
     * Return all available Menu in Menu.
     *
     * @param  void
     * @return \Illuminate\Support\Collection
     */
    public function adminMenus()
    {
        $menus = $this->all(true);
        $adminMenus = $menus->sortBy('priority');

        $result = $adminMenus->map(
            function ($item, $index) {
                $routeName = $item->route();
                if ($item->hasSubMenu()) {
                    $subMenus = collect($item->subMenu)->map(
                        function ($item) {
                            $routeName = $item->route();
                            return [
                                'icon' => $item->icon(),
                                'badge' => $item->badge(),
                                'badgeClass' => $item->badgeClass(),
                                'name' => $item->label(),
                                'url' => $routeName === '#' ? '#' : route($routeName, $item->params()),
                            ];
                        }
                    );
                }
                return [
                    'name' => $item->label(),
                    'icon' => $item->icon(),
                    'badge' => $item->badge(),
                    'badgeClass' => $item->badgeClass(),
                    'url' => $routeName === '#' ? '#' : route($routeName, $item->params()),
                    'submenus' => $subMenus ?? collect([])
                ];
            }
        );

        return $result;
    }
}
