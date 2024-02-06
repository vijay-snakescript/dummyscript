<?php

namespace App\Widgets;

use App\Helpers\Classes\AbstractWidget;
use App\Repositories\CategoryRepository;

class PostCategoriesWidet extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [
        'title' => false,
        'hierarchy' => false,
        'hide_empty_posts' => false,
        'post_counts' => false,
        'order_by' => 'order',
        'type' => 'post'
    ];

    const TITLE = 'widgets.postCategories.title';
    const DESCRIPTION = 'widgets.postCategories.description';
    const VIEW = 'widgets.post_categories';
    const ADMIN_VIEW = 'widgets.editor.post_categories';

    public function __construct(array $config = [])
    {
        $setting = isset($config['settings']) ? (array) $config['settings'] : [];
        $setting['title'] = isset($config['title']) ? $config['title'] : '';
        // $setting['style'] = isset($config['style']) ? $config['style'] : false;

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
