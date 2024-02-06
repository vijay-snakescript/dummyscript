<?php

namespace App\Helpers\Classes;

use Illuminate\Support\Collection;

class WidgetManager
{
    /**
     * Collection of Widget
     * @var object \Illuminate\Support\Collection
     */
    protected $collection;

    /**
     * Construct for the Widget manager
     *
     * @return void
     */
    public function __construct()
    {
        $this->collection = Collection::make([]);
    }

    /**
     * Add Widget Class to a collection
     * @param string $key
     * @param Widget $widget
     *
     * @return Widget $widget
     */
    public function register($key, $widget)
    {
        $this->collection->put($key, $widget);

        return $widget;
    }

    /**
     * Get the Widget from collection by given key
     * @param string $key
     *
     * @return Widget $widget
     */
    public function get($key)
    {
        return $this->collection->get($key);
    }

    /**
     * Get the Widget from collection by given key
     * @param string $widget
     *
     * @return Bool
     */
    public function find($widget)
    {
        return $this->collection->contains($widget);
    }

    /**
     * Returns All the widget in collection
     *
     * @return \Illuminate\Support\Collection
     */
    public function all()
    {
        return $this->collection;
    }
}
