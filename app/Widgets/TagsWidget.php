<?php

namespace App\Widgets;

use App\Models\Tag;
use App\Helpers\Classes\AbstractWidget;

class TagsWidget extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [
        'title' => false,
        'make' => 'limit',
        'limit' => 15,
    ];

    const TITLE = 'widgets.tags.title';
    const DESCRIPTION = 'widgets.tags.description';
    const VIEW = 'widgets.tags';
    const ADMIN_VIEW = 'widgets.editor.tags';

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
    public function run()
    {
        $title = $this->config['title'] ?? false;
        $limit = $this->config['limit'] ?? 15;
        $empty = $this->config['hide_empty'] ?? false;
        $tags = Tag::active()
            ->with('translations')
            ->limit($limit)
            ->when($empty, function ($query) {
                $query->has('posts');
            })
            ->get();

        return view(static::VIEW, [
            'title' => $title,
            'config' => $this->config,
            'tags' => $tags,
        ]);
    }
}
