<?php

namespace App\Helpers\Classes\Menu;

class MenuContainer
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $key;

    /**
     * @var string
     */
    public $priority;

    /**
     * @var calllback
     */
    public $callback;

    /**
     * @var array $items
     */
    public $items;

    /**
     *  AvoRed Front Menu Construct method.
     */
    public function __construct($callable)
    {
        $this->callback = $callable;
        $callable($this);
    }

    /**
     * Get/Set Admin Menu name.
     * @return mixed
     */
    public function name($name = null)
    {
        if (null !== $name) {
            $this->name = $name;

            return $this;
        }

        return $this->name;
    }

    /**
     * Get/Set Admin Menu Identifier.
     * @return \AvoRed\Framework\Menu\Menu|string
     */
    public function key($key = null)
    {
        if (null !== $key) {
            $this->key = $key;

            return $this;
        }

        return $this->key;
    }

    /**
     * Get/Set Admin Menu priority.
     *
     * @return \AvoRed\Framework\Menu\Menu|string
     */
    public function priority($priority = null)
    {
        if (null !== $priority) {
            $this->priority = $priority;

            return $this;
        }

        return $this->priority;
    }

    /**
     * Get/Set Admin Menu Sub Menu.
     * @param null|string $key
     * @param mixed $menuItem
     *
     * @return MenuContainer
     */
    public function items($menuItems = null)
    {
        collect($menuItems)->each(function ($item) {
            $this->item(null, $item);
        });

        return $this;
    }

    /**
     * Get/Set Admin Menu Sub Menu.
     * @param null|string $key
     * @param mixed $menuItem
     *
     * @return MenuContainer
     */
    public function item($key = null, $menuItem = null)
    {
        if (null === $menuItem) {
            return $this->items;
        }

        $menu = new RegisterMenuItem($menuItem);
        $this->items[$key] = $menu;

        return $this;
    }

    /**
     * To check if a menu has submenu or not.
     * @return bool
     */
    public function hasItems()
    {
        if (isset($this->items) && count($this->items) > 0) {
            return true;
        }

        return false;
    }
}
