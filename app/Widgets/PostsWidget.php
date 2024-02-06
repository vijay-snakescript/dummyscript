<?php

namespace App\Widgets;

use App\Helpers\Classes\AbstractWidget;
use App\Repositories\PostRepository;

class PostsWidget extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [
        'title' => false,
        'make' => 'limit',
        'limit' => 5,
        'type' => null,
        'order' => 'latest',
        'featured' => null,
    ];

    const TITLE = 'widgets.posts.title';
    const DESCRIPTION = 'widgets.posts.description';
    const VIEW = 'widgets.posts';
    const ADMIN_VIEW = 'widgets.editor.posts';

    public function __construct(array $config = [])
    {
        $setting = isset($config['settings']) ? (array) $config['settings'] : [];
        $setting['title'] = isset($config['title']) ? $config['title'] : '';

        parent::__construct($setting);
    }

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run(PostRepository $repository)
    {
        $title = $this->config['title'] ?? false;
        $layout = $this->config['layout'] ?? 'default';
        $posts = $repository->list($this->config);
        $view = static::VIEW . "_{$layout}";
        if (!$this->has_view($view)) {
            $view = static::VIEW . "_default";
        }

        return view($view, [
            'title' => $title,
            'config' => $this->config,
            'posts' => $posts,
        ]);
    }
}
