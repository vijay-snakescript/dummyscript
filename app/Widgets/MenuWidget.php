<?php

namespace App\Widgets;

use App\Models\Tool;
use App\Helpers\Classes\AbstractWidget;
use App\Models\Menu;

class MenuWidget extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    const TITLE = 'widgets.menu.title';
    const DESCRIPTION = 'widgets.menu.description';
    const VIEW = 'widgets.menu';
    const ADMIN_VIEW = 'widgets.editor.menu';

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
        $menus = Menu::get();

        return view(static::ADMIN_VIEW, compact('fields', 'sidebar', 'title', 'description', 'widget', 'menus'));
    }

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $title = $this->config['title'] ?? false;
        $settings = (array) $this->config['settings'] ?? [];
        $menu_id = (int) $settings['menu_id'] ?? null;
        $menu_style = $settings['menu_style'] ?? 'list';


        return view(static::VIEW, [
            'title' => $title,
            'settings' => $settings,
            'config' => $this->config,
            'menu_id' => $menu_id,
            'menu_style' => $menu_style,
        ]);
    }
}
