<?php

namespace App\Tools;

use App\Models\Tool;
use Iodev\Whois\Factory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Components\ToolsManager;
use App\Contracts\ToolInterface;
use Illuminate\Support\Facades\Cache;


class DomainNameSearch implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        return view('tools.domain-name-search', compact('tool'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $validated = $request->validate([
            'domain' => 'required',
            'type' => 'required'
        ]);

        $whois = Factory::get()->createWhois();
        $domain = Str::slug($request->domain, '');
        $domain_name[] = $domain . "" . $request->type;
        $available_tlds = [".com", ".net", ".org", ".co.in", ".me", ".us", ".co", ".info"];
        $output_array = [];
        foreach ($available_tlds as $tld) {
            if ($tld != $request->type) {
                $domain_name[] = $domain . "" . $tld;
            }
        }
        $output_array['search'] = $domain_name;
        $output_array['suggestions'] = $this->getSugesstionWords($request->domain, $request->type);
        $results = ['domain' => $request->domain, 'type' => $request->type, 'has_suggestions' => (count($output_array['suggestions']) > 0), 'domainAddresses' => json_encode($output_array)];

        return view('tools.domain-name-search', compact('results', 'tool'));
    }

    public function postAction(Request $request, $tool)
    {
        $domain = $request->input('domain');
        $driver = (new ToolsManager($tool))->driver();
        $results = $driver->nameAvaiability($domain);
        $content = $results['content'] ?? false;

        return $content;
    }

    public function getSugesstionWords($keyword, $type)
    {
        $list = collect([]);

        $amazon = $this->amazonSuggetions($keyword);
        $google = $this->googleSuggetions($keyword);
        $bing = $this->bingSuggetions($keyword);
        $yahoo = $this->yahooSuggetions($keyword);
        $list = $list->merge([...$amazon, ...$google, ...$bing, ...$yahoo]);
        $suggestions = $list
            ->sort()
            ->filter(function ($item) {
                return str_word_count($item) > 1 && str_word_count($item) < 4;
            })
            ->map(function ($item) use ($type) {
                return Str::of($item)->slug('')->finish($type)->tostring();
            })
            ->unique()
            ->take(20);

        return array_values($suggestions->toArray());
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

        return collect($list['gossip']['results'])->pluck('key')->toArray() ?? [];
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
