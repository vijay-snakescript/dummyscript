<?php

namespace App\Helpers\Classes\Menu;

use Illuminate\Support\Facades\Lang;

class RegisterMenuItem
{
    /**
     * @var string
     */
    public $label;

    /**
     * @var string
     */
    public $type;

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
    public $target;

    /**
     * @var string
     */
    public $key;

    /**
     * @var string
     */
    public $priority;

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

        return Lang::has($this->label, null, false)? __($this->label) : $this->label;
    }

    /**
     * Get/Set Admin Menu type.
     * @param string|null $type
     * @return \AvoRed\Framework\Menu\Menu|string
     */
    public function type($type = null)
    {
        if (null !== $type) {
            $this->type = $type;

            return $this;
        }

        return trans($this->type);
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
    public function route($routeName = null, $params = [])
    {
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
    public function target($target = null)
    {
        if (null !== $target) {
            $this->target = $target;

            return $this;
        }

        return $this->target;
    }
}
