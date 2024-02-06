<?php

namespace App\Widgets;

use App\Helpers\Classes\AbstractWidget;

class HtmlWidget extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    const TITLE = 'widgets.html.title';
    const DESCRIPTION = 'widgets.html.description';
    const VIEW = 'widgets.html';
    const ADMIN_VIEW = 'widgets.editor.html';

    public function __construct(array $config = array())
    {
        parent::__construct($config);

        $this->set_fields('ajax', false);
    }

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $title = $this->config['title'] ?? false;
        $settings = (object) $this->config['settings'] ?? [];
        $html = $settings->code ?? false;

        return view(static::VIEW, [
            'title' => $title,
            'settings' => $settings,
            'config' => $this->config,
            'html' => $html,
        ]);
    }
}
