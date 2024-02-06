<?php

namespace App\Providers;

use App\Models\WidgetArea;
use App\Helpers\Facads\Widgets;
use Jenssegers\Agent\Facades\Agent;
use App\Helpers\Classes\WidgetManager;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class WidgetsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerServices();
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->initWidget();
        $this->registerWidgets();
    }

    /**
     * Register the Admin Menu instance.
     *
     * @return void
     */
    protected function registerServices()
    {
        $this->app->singleton('ArtisanWidget', function ($app) {
            return new WidgetManager();
        });
    }

    /**
     * Register the Widget
     * @return void
     */
    protected function initWidget()
    {
        Widgets::register('artisan-related-tools', 'App\Widgets\RelatedToolsWidget');
        Widgets::register('artisan-popular-tools', 'App\Widgets\PopularToolsWidget');
        Widgets::register('artisan-custom-tools', 'App\Widgets\CustomToolsWidet');
        Widgets::register('artisan-tool-categories', 'App\Widgets\ToolCategoriesWidget');
        Widgets::register('artisan-menu', 'App\Widgets\MenuWidget');
        Widgets::register('artisan-text', 'App\Widgets\TextWidget');
        // Widgets::register('artisan-html', 'App\Widgets\HtmlWidget');
        Widgets::register('artisan-advertisement', 'App\Widgets\Advertisement');
        Widgets::register('artisan-posts', 'App\Widgets\PostsWidget');
        Widgets::register('artisan-posts-categories', 'App\Widgets\PostCategoriesWidet');
        Widgets::register('artisan-posts-tags', 'App\Widgets\TagsWidget');
    }

    /**
     * Register the Widget areas
     * @return void
     */
    public function registerWidgets()
    {
        if (!Config::get('artisan.installed')) {
            return;
        }

        $sidebars = WidgetArea::with(
            ['widgets' => function ($q) {
                $q->active();
                if (Agent::isDesktop()) {
                    $q->web();
                }

                if (Agent::isMobile() || Agent::isTablet()) {
                    $q->mobile();
                }
            }]
        )->get();

        foreach ($sidebars as $sidebar) {
            $widgets = $sidebar->widgets;
            foreach ($widgets as $widget) {
                if (!Widgets::find($widget->name) || !method_exists($widget->name, 'run')) {
                    continue;
                }

                $config = [
                    'title' => $widget->title,
                    'settings' => $widget->settings,
                ];

                if ($widget->ajax === 1) {
                    \Widget::group($sidebar->name)->position($widget->order)->addAsyncWidget($widget->name, $config);
                } else {
                    \Widget::group($sidebar->name)->position($widget->order)->addWidget($widget->name, $config);
                }
            }
        }
    }
}
