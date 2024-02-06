<?php

namespace App\Tools;

use App\Models\Tool;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Components\ToolsManager;
use App\Contracts\ToolInterface;
use Illuminate\Support\Facades\Cache;
use App\Rules\MultipleMaxlinesValidator;

class KeywordPosition implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        $countries = get_google_country();
        return view('tools.keyword-position', compact('tool', 'countries'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $request->validate([
            'url' => 'required|url',
            'keywords' => ['required', new MultipleMaxlinesValidator($tool->no_domain_tool ?? 3)],
        ]);

        $countries = get_google_country();
        $process_id = (string) Str::uuid();
        $results = Cache::remember($process_id, job_cache_time(), function () use ($request) {
            return [
                'keywords' => $request->keywords,
                'keywords_data' => json_encode(explode(PHP_EOL, $request->keywords)),
                'url' => $request->url,
                'country' => $request->country ?? 'us',
                'competitors' => $request->competitors,
            ];
        });

        return view('tools.keyword-position', compact('results', 'tool', 'countries', 'process_id'));
    }

    public function postAction(Request $request, $tool)
    {
        $inputs = Cache::get($request->process_id);
        $inputs['q'] = $request->keyword;
        $inputs['pages'] = 1;
        $url = $inputs['url'];
        $competitors = $inputs['competitors'];

        $driver = (new ToolsManager($tool))->driver();
        $result = $driver->parse($inputs);

        // Match URL
        $positionExact = $result['items']->where('link', $url);
        $positionMatching = $result['items']->filter(function ($item) use ($url) {
            return false !== stripos($item['link'], extractHostname($url, true));
        });

        $competitorsData = [];
        foreach ($competitors as $index => $competitor) {
            $competitorsData[($index + 1)] = [];
            if (!empty($competitor)) {
                // Match competitor
                $exact = $result['items']->where('link', $competitor);
                $matching = $result['items']->filter(function ($item) use ($competitor) {
                    return false !== stripos($item['link'], extractHostname($competitor, true));
                });

                $competitorsData[$index] = [
                    'url' => $competitor,
                    'exact' => !empty(array_keys($exact->toArray())) ? array_keys($exact->toArray())[0] + 1 : __('tools.notInTop'),
                    'matching' => !empty(array_keys($matching->toArray())) ? array_keys($matching->toArray())[0] + 1 : __('tools.notInTop'),
                ];
            }
        }

        $results = [
            'keywords' => $request->keyword,
            'url' => [
                'url' => $url,
                'exact' => !empty(array_keys($positionExact->toArray())) ? array_keys($positionExact->toArray())[0] + 1 : __('tools.notInTop'),
                'matching' => !empty(array_keys($positionMatching->toArray())) ? array_keys($positionMatching->toArray())[0] + 1 : __('tools.notInTop'),
            ],
            'competitors' => $competitorsData,
            'volumn' => $result['stats']['totalResults'] ?? 0,
            'volumn_formated' => $result['stats']['formattedTotalResults'] ?? 0,
            'volumn_short' => format_number($result['stats']['totalResults']),
            'content' => $result['items']->pluck('link')->toArray(),
        ];

        return response()->json($results);
    }

    public static function getFileds()
    {
        $array = [
            'title' => "Drivers",
            'fields' => [
                [
                    'id' => "driver",
                    'field' => "tool-options-select",
                    'placeholder' => "Driver",
                    'label' => "Driver",
                    'required' => true,
                    'options' => [['text' => "Google Search Api", 'value' => "GoogleSearchApi"]],
                    'validation' => "required",
                    'type' => 'dropdown',
                    'classes' => "",
                    'dependant' => null,
                ],
                [
                    'id' => "google_Search_apikey",
                    'field' => "tool-options-textfield",
                    'placeholder' => "please enter api key here....",
                    'label' => "Google Search Api Key",
                    'required' => true,
                    'options' => null,
                    'validation' => "required_if:driver,GoogleSearchApi",
                    'type' => 'text',
                    'min' => null,
                    'max' => null,
                    'classes' => "",
                    'dependant' => ['settings[driver]', "GoogleSearchApi"],
                ],
                [
                    'id' => "google_Search_cx",
                    'field' => "tool-options-textfield",
                    'placeholder' => "please enter CX key here....",
                    'label' => "Google Search CX Key",
                    'required' => true,
                    'options' => null,
                    'validation' => "required_if:driver,GoogleSearchApi",
                    'type' => 'text',
                    'min' => null,
                    'max' => null,
                    'classes' => "",
                    'dependant' => ['settings[driver]', "GoogleSearchApi"],
                ],
            ],
            "default" => ['driver' => 'GoogleSearchApi']
        ];

        return $array;
    }
}
