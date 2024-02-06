<?php

namespace App\Tools;

use App\Models\Tool;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Contracts\ToolInterface;
use Illuminate\Support\Facades\Cache;

class RelatedKeywordsFinder implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        return view('tools.related-keyword-finder', compact('tool'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $validated = $request->validate([
            'keyword' => 'required|string|min:3',
        ]);

        $keywords = $this->getSugesstionWords($request->keyword);
        $results = [
            'keyword' => $request->keyword,
            'count' => count($keywords),
            'has_suggestions' => (count($keywords) > 0),
            'suggestions' => $keywords,
        ];

        return view('tools.related-keyword-finder', compact('results', 'tool'));
    }

    public function postAction(Request $request, $tool)
    {
        $results =  $this->getSugesstionWords($request->keyword);

        return $results;
    }

    public function getSugesstionWords($keyword)
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
                return str_word_count($item) > 1 && str_word_count($item) < 6;
            })
            ->unique();

        return array_values($suggestions->toArray());
    }

    protected function googleSuggetions($keyword)
    {
        $endpoint = "http://suggestqueries.google.com/complete/search?output=chrome&&hl=en&q=" . $keyword;
        $list = Cache::rememberForever(Str::slug($keyword) . 'google-suggestions-keywords', function () use ($endpoint) {
            $json = makeHttpRequest($endpoint);
            $list = json_decode($json, TRUE);

            return $list;
        });

        return $list[1] ?? [];
    }

    protected function bingSuggetions($keyword)
    {
        $endpoint = "https://api.bing.com/osjson.aspx?JsonType=callback&JsonCallback&Query={$keyword}&Market=en-us";
        $list = Cache::rememberForever(Str::slug($keyword) . 'bing-suggestions-keywords', function () use ($endpoint) {
            $json = makeHttpRequest($endpoint);
            $list = json_decode($json, TRUE);

            return $list;
        });

        return $list[1] ?? [];
    }

    protected function yahooSuggetions($keyword)
    {
        $endpoint = "https://ff.search.yahoo.com/gossip?output=json&nresults=20&command={$keyword}";
        $list = Cache::rememberForever(Str::slug($keyword) . 'yahoo-suggestions-keywords', function () use ($endpoint) {
            $json = makeHttpRequest($endpoint);
            $list = json_decode($json, TRUE);

            return $list;
        });

        return collect($list['gossip']['results'] ?? [])->pluck('key')->toArray() ?? [];
    }

    protected function amazonSuggetions($keyword)
    {
        $endpoint = "https://completion.amazon.com/search/complete?q={$keyword}&method=completion&search-alias=aps&mkt=1";
        $list = Cache::rememberForever(Str::slug($keyword) . 'amazon-suggestions-keywords', function () use ($endpoint) {
            $json = makeHttpRequest($endpoint);
            $list = json_decode($json, TRUE);

            return $list;
        });

        return $list['gossip']['results'] ?? [];
    }
}
