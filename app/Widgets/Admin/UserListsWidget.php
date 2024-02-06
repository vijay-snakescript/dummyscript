<?php

namespace App\Widgets\Admin;

use Arrilot\Widgets\AbstractWidget;
use App\Repositories\PostRepository;
use Theme;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Post;

class UserListsWidget extends AbstractWidget
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

    public function container() {
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
        $range = Carbon::now()->subDays($this->stats_days);
        $agoDate = Carbon::today()->subWeek();

        //Stats Snippet array
        $users = User::latest()->take(10)->get();

        return view('widgets.admin.userLists', [
            'config' => $this->config,
            'users' => $users,
        ]);
    }
}
