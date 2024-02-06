<?php

namespace App\Widgets;

use App\Models\Tool;
use App\Helpers\Classes\AbstractWidget;

class CustomToolsWidet extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    const TITLE = 'widgets.custom_tools.title';
    const DESCRIPTION = 'widgets.custom_tools.description';
    const VIEW = 'widgets.tools';
    const ADMIN_VIEW = 'widgets.editor.custom_tools';

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
        $tools = Tool::with('translations')->get();

        return view(static::ADMIN_VIEW, compact('fields', 'sidebar', 'title', 'description', 'widget', 'tools'));
    }

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $title = $this->config['title'] ?? false;
        $settings = (object) $this->config['settings'] ?? [];
        $ids = $settings->ids ?? [];

        $tools = Tool::with('translations')->whereIn('id', $ids)->active()->get();

        return view(static::VIEW, [
            'title' => $title,
            'settings' => $settings,
            'config' => $this->config,
            'tools' => $tools,
        ]);
    }
}
