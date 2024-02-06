<?php

namespace App\Widgets;

use App\Models\Tool;
use App\Helpers\Classes\AbstractWidget;

class PopularToolsWidget extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    const TITLE = 'widgets.popular_tools.title';
    const DESCRIPTION = 'widgets.popular_tools.description';
    const VIEW = 'widgets.tools';
    const ADMIN_VIEW = 'widgets.editor.popular_tools';

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $title = $this->config['title'] ?? false;
        $settings = (object) $this->config['settings'] ?? [];
        $limit = $settings->limit ?? 10;
        $tools = Tool::orderByViews('desc')->with('translations')->active()->limit($limit)->get();

        return view(static::VIEW, [
            'title' => $title,
            'settings' => $settings,
            'config' => $this->config,
            'tools' => $tools,
        ]);
    }
}
