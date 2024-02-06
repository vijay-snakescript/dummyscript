<?php

namespace App\Tools;

use App\Models\Tool;
use Illuminate\Http\Request;
use App\Components\ToolsManager;
use App\Contracts\ToolInterface;

class SerpChecker implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        $countries = get_google_country();

        return view('tools.serp-checker', compact('tool', 'countries'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $validated = $request->validate([
            'keyword' => 'required|string',
        ]);

        $inputs = [
            'q' => $request->keyword,
            'pages' => 5,
            'country' => $request->country ?? 'us',
            'host' => "google.com",
        ];

        // parse with driver
        $driver = (new ToolsManager($tool))->driver();
        $result = $driver->parse($inputs);
        $results = [
            'keyword' => $request->keyword,
            'country' => $request->country ?? 'us',
            'content' => $result,
            'searchTime' => $result['stats']['searchTime'],
            'total_results' => $result['stats']['totalResults'],
            'total_results_formated' => $result['stats']['formattedTotalResults'],
            'total_results_short' => format_number($result['stats']['totalResults']),
            'trends' => 'https://trends.google.com:443/trends/embed/explore/TIMESERIES?req={"comparisonItem":[{"keyword":"' . $request->keyword . '","geo":"' . strtoupper($request->country ?? 'us') . '","time":"today 12-m"}],"category":0,"property":""}&tz=-300&eq=geo=' . strtoupper($request->country ?? 'us') . '&q=' . $request->keyword . '&date=today 12-m',
        ];
        $countries = get_google_country();

        return view('tools.serp-checker', compact('results', 'tool', 'countries'));
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
