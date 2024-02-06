<?php

namespace App\Widgets\Admin;

use Theme;
use App\Models\Tool;
use Arrilot\Widgets\AbstractWidget;
use App\Repositories\PostRepository;

class ToolListsWidget extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * The number of seconds before each reload.
     *
     * @var int|float
     */
    public $reloadTimeout = 60;

    /**
     * The number of minutes before cache expires.
     * False means no caching at all.
     *
     * @var int|float|bool
     */
    public $cacheTime = 60;

    /**
     * Generate stats for N days
     */
    public $stats_days = 30;

    /**
     * Instance of post Repositories
     *
     * @param  App\Repositories\PostRepository  $postRepository
     */
    protected $postRepository;

    public function __construct(array $config, PostRepository $postRepository)
    {
        Theme::set('admin');
        parent::__construct($config);
        $this->postRepository = $postRepository;
    }

    public function container()
    {
        return [
            'element' => 'div',
            'attributes' => 'class="col-md-6"',
        ];
    }

    /**
     * Async widgets placeholder
     */
    public function placeholder()
    {
        return __('widgets.admin.loadingGraphs');
    }

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        //Stats Snippet array
        $tools = Tool::withCount(['views', 'thisWeek', 'lastWeek'])
            ->with('translations')
            ->orderByDesc('this_week_count')
            ->latest()
            ->take(10)
            ->get();

        return view('widgets.admin.toolLists', [
            'config' => $this->config,
            'tools' => $tools,
        ]);
    }
}
