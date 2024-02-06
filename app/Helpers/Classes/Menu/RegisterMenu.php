<?php

namespace App\Helpers\Classes\Menu;

use Illuminate\Support\Collection;

class RegisterMenu
{
    /**
     * Collection of Widget
     * @var object \Illuminate\Support\Collection
     */
    protected $collection;

    /**
     * Construct for the Widget manager
     * @return void
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
    public function register($key, callable $callable, $order = null)
    {
        $priority = $order === null ? $this->collection->count() : $order;
        $menu = new MenuContainer($callable);
        $menu->key($key);
        $menu->priority($priority);
        $this->collection->put($key, $menu);

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
     * Get the Widget from collection by given key
     * @param string $widget
     * @return Bool
     */
    public function find($menu)
    {
        return $this->collection->contains($menu);
    }

    /**
     * Returns All the widget in collection
     * @return \Illuminate\Support\Collection
     */
    public function all()
    {
        return $this->collection->sortBy('priority');
    }
}
