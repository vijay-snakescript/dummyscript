<?php

namespace App\Tools;

use Exception;
use App\Models\Tool;
use Iodev\Whois\Factory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Components\ToolsManager;
use App\Contracts\ToolInterface;
use Illuminate\Support\Facades\Cache;
use App\Components\Drivers\NullDriver;

class KeywordsRichDomainsSuggestionsTool implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        $tlds = $this->getSupportedTlds();

        return view('tools.keywords-rich-domains-suggestions-tool', compact('tool', 'tlds'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $validated = $request->validate([
            'keyword' => 'required|min:3',
            'tlds' => 'required|array',
        ]);

        $driver = (new ToolsManager($tool))->driver();
        if ($driver instanceof NullDriver) {
            throw new Exception(__("common.toolDriverNotAvailable"));
        }

        $whois = Factory::get()->createWhois();
        $keyword = Str::slug($request->keyword, '');
        $selectedTlds = $request->input('tlds', []);
        $domain_name = $output_array = [];
        $tlds = $this->getSupportedTlds();
        foreach ($selectedTlds as $tld) {
            $domain_name[] = $keyword . "" . $tld;
        }

        $output_array['search'] = $domain_name;
        $output_array['suggestions'] = $this->getSugesstionWords($request->keyword, $selectedTlds);
        $results = ['tlds' => $selectedTlds, 'keyword' => $request->keyword, 'type' => $request->type, 'has_suggestions' => (count($output_array['suggestions']) > 0), 'domainAddresses' => $output_array];

        return view('tools.keywords-rich-domains-suggestions-tool', compact('results', 'tool', 'tlds'));
    }

    public function postAction(Request $request, $tool)
    {
        $domain = $request->input('domain');
        $driver = (new ToolsManager($tool))->driver();
        $results = $driver->nameAvaiability($domain);
        $content = $results['content'] ?? false;

        return $content;
    }

    private function getSupportedTlds()
    {
        return [
            '.com',
            '.net',
            '.org',
            '.us',
            '.info',
            '.co.in',
            '.me',
            '.co',
        ];
    }

    public function getSugesstionWords($keyword, $tlds)
    {
        $list = collect([]);

        $amazon = $this->amazonSuggetions($keyword);
        $google = $this->googleSuggetions($keyword);
        $bing = $this->bingSuggetions($keyword);
        $yahoo = $this->yahooSuggetions($keyword);
        $list = $list->merge([...$amazon, ...$google, ...$bing, ...$yahoo]);
        $result = [];
        $suggestions = $list
            ->sort()
            ->filter(function ($item) {
                return str_word_count($item) > 1 && str_word_count($item) < 4;
            })
            ->unique()
            ->take(20)
            ->each(function ($keyword, $key) use ($tlds, &$result) {
                foreach ($tlds as $tld) {
                    $result[$tld][$key]['keyword'] = $keyword;
                    if (str_word_count($keyword) == 1) {
                        $result[$tld][$key]['domains'] = [
                            Str::of($keyword)->slug('')->finish($tld)->tostring(),
                        ];
                    } else {
                        $result[$tld][$key]['domains'] = [
                            Str::of($keyword)->slug('')->finish($tld)->tostring(),
                            Str::of($keyword)->slug('-')->finish($tld)->tostring()
                        ];
                    }
                }
            });

        return $result;
    }

    protected function googleSuggetions($keyword)
    {
        $endpoint = "http://suggestqueries.google.com/complete/search?output=chrome&&hl=en&q=" . $keyword;
        $list = Cache::rememberForever(Str::slug($keyword) . 'google-suggestions', function () use ($endpoint) {
            $json = makeHttpRequest($endpoint);
            $list = json_decode($json, TRUE);

            return $list;
        });

        return $list[1] ?? [];
    }

    protected function bingSuggetions($keyword)
    {
        $endpoint = "https://api.bing.com/osjson.aspx?JsonType=callback&JsonCallback&Query={$keyword}&Market=en-us";
        $list = Cache::rememberForever(Str::slug($keyword) . 'bing-suggestions', function () use ($endpoint) {
            $json = makeHttpRequest($endpoint);
            $list = json_decode($json, TRUE);

            return $list;
        });

        return $list[1] ?? [];
    }

    protected function yahooSuggetions($keyword)
    {
        $endpoint = "https://ff.search.yahoo.com/gossip?output=json&nresults=20&command={$keyword}";
        $list = Cache::rememberForever(Str::slug($keyword) . 'yahoo-suggestions', function () use ($endpoint) {
            $json = makeHttpRequest($endpoint);
            $list = json_decode($json, TRUE);

            return $list;
        });

        return collect($list['gossip']['results'] ?? [])->pluck('key')->toArray() ?? [];
    }

    protected function amazonSuggetions($keyword)
    {
        $endpoint = "https://completion.amazon.com/search/complete?q={$keyword}&method=completion&search-alias=aps&mkt=1";
        $list = Cache::rememberForever(Str::slug($keyword) . 'amazon-suggestions', function () use ($endpoint) {
            $json = makeHttpRequest($endpoint);
            $list = json_decode($json, TRUE);

            return $list;
        });

        return $list['gossip']['results'] ?? [];
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
                    'options' => [['text' => "Default", 'value' => "WhoisDomainChecker"]],
                    'validation' => "required",
                    'type' => 'dropdown',
                    'classes' => "",
                    'dependant' => null,
                ],
            ],
            "default" => ['driver' => 'WhoisDomainChecker']
        ];

        return $array;
    }
}
