<?php

namespace App\Helpers\Classes;

use App\Contracts\MenuInterface;
use Illuminate\Queue\NullQueue;

class MenuItems implements MenuInterface
{
    /**
     * Constant Front.
     * @var string FRONT
     */
    const FRONT = 'front';

    /**
     * Constant Admin.
     * @var string ADMIN
     */
    const ADMIN = 'admin';

    /**
     * @var string
     */
    public $label;

    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $badge;

    /**
     * @var string
     */
    public $badgeClass = 'primary';

    /**
     * @var string|null
     */
    public $classes;

    /**
     * @var string|null
     */
    public $tag = null;

    /**
     * @var string
     */
    public $icon;

    /**
     * @var array
     */
    public $attributes;

    /**
     * @var string
     */
    public $key;

    /**
     * @var string
     */
    public $params;

    /**
     * @var string
     */
    public $routeName;

    /**
     * @var calllback
     */
    public $callback;

    /**
     * @var array $subMenu
     */
    public $subMenu;

    /**
     *  AvoRed Front Menu Construct method.
     */
    public function __construct($callable)
    {
        $this->callback = $callable;
        $callable($this);
    }

    /**
     * Get/Set Admin Menu Label.
     * @param string|null $label
     * @return \AvoRed\Framework\Menu\Menu|string
     */
    public function label($label = null)
    {
        if (null !== $label) {
            $this->label = $label;

            return $this;
        }

        return trans($this->label);
    }

    /**
     * Get/Set Admin Menu Type.
     * @return mixed
     */
    public function type($type = null)
    {
        if (null !== $type) {
            $this->type = $type;

            return $this;
        }

        return $this->type;
    }

    /**
     * Get/Set Admin Menu Type.
     * @return mixed
     */
    public function badge($badge = null)
    {
        if (null !== $badge) {
            $this->badge = $badge;

            return $this;
        }

        return $this->badge;
    }
    /**
     * Get/Set Admin Menu Type.
     * @return mixed
     */
    public function badgeClass($class = null)
    {
        if (null !== $class) {
            $this->badgeClass = $class;

            return $this;
        }

        return $this->badgeClass;
    }

    /**
     * Get/Set Admin Menu Type.
     * @return mixed
     */
    public function classes($classes = null)
    {
        if (null !== $classes) {
            $this->classes = $classes;

            return $this;
        }

        return $this->classes;
    }

    /**
     * Get/Set Admin Menu Type.
     * @return mixed
     */
    public function tag($tag = null)
    {
        if (null !== $tag) {
            $this->tag = $tag;

            return $this;
        }

        return $this->tag;
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
     * Get/Set Admin Menu Route Name.
     * @return \AvoRed\Framework\Menu\Menu|string
     */
    public function route($routeName = null, $params = null)
    {
        if (null !== $params) {
            $this->params($params);
        }

        if (null !== $routeName) {
            $this->routeName = $routeName;

            return $this;
        }


        return $this->routeName;
    }

    /**
     * Get/Set Admin Menu Route Params Name.
     * @return \AvoRed\Framework\Menu\Menu|string
     */
    public function params($params = null)
    {
        if (null !== $params) {
            $this->params = $params;

            return $this;
        }

        return $this->params;
    }

    /**
     * Get/Set Admin Menu Icon.
     * @return \AvoRed\Framework\Menu\Menu|string
     */
    public function icon($icon = null)
    {
        if (null !== $icon) {
            $this->icon = $icon;

            return $this;
        }

        return $this->icon;
    }

    /**
     * Get/Set Admin Menu Icon.
     * @return \AvoRed\Framework\Menu\Menu|string
     */
    public function attributes($attributes = null)
    {
        if (null !== $attributes) {
            $this->attributes = $attributes;

            return $this;
        }

        return $this->attributes;
    }

    /**
     * Get/Set Admin Menu Sub Menu.
     * @param null|string $key
     * @param mixed $menuItem
     * @return \AvoRed\Framework\AdminMenu\AdminMenu
     */
    public function subMenu($key = null, $menuItem = null)
    {
        if (null === $menuItem) {
            return $this->subMenu;
        }
        $menu = new self($menuItem);
        $this->subMenu[$key] = $menu;

        return $this;
    }

    /**
     * To check if a menu has submenu or not.
     * @return bool
     */
    public function hasSubMenu()
    {
        if (isset($this->subMenu) && count($this->subMenu) > 0) {
            return true;
        }

        return false;
    }
}
