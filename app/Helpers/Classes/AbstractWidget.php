<?php

namespace App\Helpers\Classes;

use Illuminate\Support\Facades\View;
use Arrilot\Widgets\AbstractWidget as WidgetAbstract;

abstract class AbstractWidget extends WidgetAbstract
{
    /**
     * Collection of Widget
     *
     * @var object \Illuminate\Support\Collection
     */
    protected $collection;

    const TITLE = 'widgets.noTitle';
    const DESCRIPTION = 'widgets.noDescription';
    const VIEW = false;
    const ADMIN_VIEW = false;

    /**
     * The configuration array.
     *
     * @var array
     */
    protected $fields = [
        'title' => true,
        'web' => true,
        'mobile' => true,
        'ajax' => true,
        'status' => true
    ];

    /**
     * Get translated title of widget
     *
     * @return String
     */
    public function get_title()
    {
        return __(static::TITLE);
    }

    /**
     * Get translated description of widget
     *
     * @return String
     */
    public function get_description()
    {
        return __(static::DESCRIPTION);
    }

    /**
     * async widget loading placeholder
     *
     * @return String
     */
    public function placeholder()
    {
        return __('common.loading');
    }

    /**
     * Get default fields
     *
     * @return String
     */
    public function get_fields()
    {
        return $this->fields;
    }

    /**
     * Set/ fields option
     *
     * @return String
     */
    public function set_fields($index, $value)
    {
        if (array_key_exists($index, $this->fields)) {
            $this->fields[$index] = $value;
        }
    }

    /**
     * Async and reloadable widgets are wrapped in container.
     * You can customize it by overriding this method.
     *
     * @return array
     */
    public function container()
    {
        return [
            'element'       => 'div',
            'attributes'    => 'class="widget-container"',
        ];
    }

    /**
     * Cache key that is used if caching is enabled.
     *
     * @param $params
     *
     * @return string
     */
    public function cacheKey(array $params = [])
    {
        return 'artisan.widgets.' . serialize($params);
    }

    /**
     * Genderate the UI for backedn widgets area.
     *
     * @return \Illuminate\Http\Response
     */
    public function build($sidebar = false, $widget = [])
    {
        if (!$this->admin_view()) {
            return;
        }

        $fields = $this->get_fields();
        $title = $this->get_title();
        $description = $this->get_description();

        return view(static::ADMIN_VIEW, compact('fields', 'sidebar', 'title', 'description', 'widget'));
    }

    /**
     * Check if admin settings view file exists
     *
     * @return bool
     */
    public function admin_view()
    {
        return $this->has_view(static::ADMIN_VIEW);
    }

    /**
     * Check if front widget view file exists
     *
     * @return bool
     */
    public function viewable()
    {
        return $this->has_view(static::VIEW);
    }

    /**
     * Check if view file exists
     *
     * @param String $view
     *
     * @return bool
     */
    public function has_view($view)
    {
        return ($view && View::exists($view));
    }
}
