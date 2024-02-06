<?php

namespace App\Widgets\Admin;

use Theme;
use Carbon\Carbon;
use App\Models\Post;
use App\Models\Tool;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Shetabit\Visitor\Models\Visit;
use Arrilot\Widgets\AbstractWidget;
use App\Repositories\PostRepository;
use CyrildeWit\EloquentViewable\View;
use CyrildeWit\EloquentViewable\Support\Period;

class GraphWidget extends AbstractWidget
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
            'attributes' => 'class="col-md-8"',
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
        $visits = $tools = [];
        //Stats Snippet array
        $toolsStats = View::query()
            ->where('viewable_type', Tool::class)
            ->where('viewed_at', '>=', $range)
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get([
                DB::raw('Date(viewed_at) as date'),
                DB::raw('COUNT(*) as value')
            ]);

        foreach ($toolsStats as $index => $tool) {
            $tools[$tool->date] = $tool->value;
        }

        $visitsStats = Visit::query()
            ->where('visitable_type', Tool::class)
            ->where('created_at', '>=', $range)
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get([
                DB::raw('Date(created_at) as date'),
                DB::raw('COUNT(*) as value')
            ]);

        foreach ($visitsStats as $index => $visit) {
            $visits[$visit->date] = $visit->value;
        }

        $dates = array();
        for ($i = 0; $i <= $this->stats_days; $i++) {
            $current = $range->copy()->addDays($i)->format('Y-m-d');
            $dates['labels'][$i] = $range->copy()->addDays($i)->format('d');
            $dates['tools'][$i] = isset($tools[$current]) ? $tools[$current] : 0;
            $dates['visits'][$i] = isset($visits[$current]) ? $visits[$current] : 0;
        }

        // $dataSets = [
        //     'lables' => $dates['labels'],
        //     'datasets' => [
        //         [
        //             'label' => __('admin.views'),
        //             'data' => $dates['tools'],
        //             'fill' => false,
        //             'borderColor' => '#2196f3', // Add custom color border (Line)
        //             'backgroundColor' => '#2196f3', // Add custom color background (Points and Fill)
        //             'borderWidth' => 1 // Specify bar border width
        //         ],
        //         [
        //             'label' => __('admin.usage'),
        //             'data' => $dates['visits'],
        //             'fill' => false,
        //             'borderColor' => '#2196f3', // Add custom color border (Line)
        //             'backgroundColor' => '#2196f3', // Add custom color background (Points and Fill)
        //             'borderWidth' => 1 // Specify bar border width
        //         ],
        //     ],
        // ];

        // $graphData = json_encode($dataSets);

        return view('widgets.admin.graphs', [
            'config' => $this->config,
            'graphData' => $dates,
        ]);
    }
}
