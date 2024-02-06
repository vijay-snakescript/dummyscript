<?php

namespace App\Widgets;

use App\Models\Tool;
use App\Helpers\Classes\AbstractWidget;

class RelatedToolsWidget extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    const TITLE = 'widgets.related_tools.title';
    const DESCRIPTION = 'widgets.related_tools.description';
    const VIEW = 'widgets.tools';
    const ADMIN_VIEW = 'widgets.editor.related_tools';

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $title = $this->config['title'] ?? false;
        $settings = (object) $this->config['settings'] ?? [];
        $limit = $settings->limit ?? 10;
        $tools = Tool::with('translations')->limit($limit)->inRandomOrder()->active()->get();

        return view(static::VIEW, [
            'title' => $title,
            'settings' => $settings,
            'config' => $this->config,
            'tools' => $tools,
        ]);
    }
}
