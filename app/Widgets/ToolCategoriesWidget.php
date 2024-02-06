<?php

namespace App\Widgets;

use App\Helpers\Classes\AbstractWidget;
use App\Repositories\CategoryRepository;

class ToolCategoriesWidget extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [
        'title' => false,
        'hide_empty_tools' => false,
        'tool_counts' => false,
        'order_by' => 'order',
        'type' => 'tool'
    ];

    const TITLE = 'widgets.toolCategories.title';
    const DESCRIPTION = 'widgets.toolCategories.description';
    const VIEW = 'widgets.tool_categories';
    const ADMIN_VIEW = 'widgets.editor.tool_categories';

    public function __construct(array $config = [])
    {
        $setting = isset($config['settings']) ? (array) $config['settings'] : [];
        $setting['title'] = isset($config['title']) ? $config['title'] : '';
        $setting['transparent'] = isset($config['transparent']) ? $config['transparent'] : '';

        parent::__construct($setting);
    }

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run(CategoryRepository $repository)
    {
        $title = $this->config['title'] ?? false;
        $categories = $repository->get($this->config);

        return view(static::VIEW, [
            'title' => $title,
            'config' => $this->config,
            'categories' => $categories
        ]);
    }
}
