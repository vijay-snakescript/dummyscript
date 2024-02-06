<?php

namespace App\Widgets\Admin;

use Theme;
use Carbon\Carbon;
use App\Models\Post;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Arrilot\Widgets\AbstractWidget;
use App\Repositories\PostRepository;

class RecentActivityWidget extends AbstractWidget
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
            'attributes' => 'class="col-md-4"',
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

        //Stats Snippet array
        $transactions = Transaction::with(['plan', 'user'])->whereHas('plan', function ($query) {
            $query->orWhere('transactions.plan_id', 0);
        })
            ->has('user')
            ->active()
            ->latest()
            ->take(10)
            ->get();

        return view('widgets.admin.recentActivity', [
            'config' => $this->config,
            'transactions' => $transactions,
        ]);
    }
}
