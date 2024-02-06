<?php

namespace App\Widgets;

use App\Helpers\Classes\AbstractWidget;

class TextWidget extends AbstractWidget
{
    const TITLE = 'widgets.text.title';
    const DESCRIPTION = 'widgets.text.description';
    const VIEW = 'widgets.text';
    const ADMIN_VIEW = 'widgets.editor.text';

    public function __construct(array $config = [])
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
        $settings = $this->config['settings'] ?? [];
        $text = $settings->text ?? false;

        return view(static::VIEW, [
            'title' => $title,
            'text' => $text,
            'settings' => $settings,
            'config' => $this->config,
        ]);
    }
}
