<?php

namespace App\Components\Drivers;

use Exception;
use App\Models\Tool;
use GuzzleHttp\Client;
use App\Contracts\ToolDriverInterface;

class GoogleSearchApi implements ToolDriverInterface
{
    private $tool;
    private $inputs;
    protected $endpoint = "https://www.googleapis.com/customsearch/v1";

    public function __construct(Tool $tool)
    {
        $this->tool = $tool;
    }

    public function parse($inputs)
    {
        $this->inputs = $inputs;

        // try {
        if (!isset($this->tool->settings->google_Search_apikey) || !isset($this->tool->settings->google_Search_cx) || empty($this->tool->settings->google_Search_apikey) || empty($this->tool->settings->google_Search_cx)) {
            $message = __('common.apiKeyNotProvided');

            return compact('inputs', 'message');
        }

        $params = [
            'query' => [
                'key' => $this->tool->settings->google_Search_apikey,
                'cx' => $this->tool->settings->google_Search_cx,
                'q' => $inputs['q'],
                'lr' => $inputs['country'],
                'gl' => $inputs['country'],
                'safe' => 'active',
                // 'googlehost' => $inputs['host'] ?? "google.com",
                'start' => 1,
            ]
        ];

        $results = ['items' => collect([]), 'stats' => []];
        $client = new Client();
        $pages = $inputs['pages'] ?? 1;
        for ($page = 0; $page < $pages; $page++) {
            $params['query']['start'] = (10 * $page) + 1;
            $response = $client->request('GET', $this->endpoint, $params);
            $body = json_decode($response->getBody(), true);
            $results['items']->push(...$body['items']);
            $results['stats'] = $body['searchInformation'];
        }
        // return $body;
        // } catch (Exception $e) {
        //     $message = __('common.somethingWentWrong');

        //     return compact('inputs', 'message');
        // }

        return $results;
    }

    protected function results($info)
    {
        return;
    }
}
