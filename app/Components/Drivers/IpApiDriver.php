<?php

namespace App\Components\Drivers;

use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use App\Contracts\ToolDriverInterface;

class IpApiDriver implements ToolDriverInterface
{
    protected $endpoint = "http://ip-api.com/json/";

    public function parse($ip)
    {
        // $ip = $this->validateIp($ip);
        $endpoint = $this->endpoint . $ip;
        $cacheKey = (empty($ip) ? (string) Str::uuid() : Str::slug($ip)) . '-ip';
        $content = Cache::rememberForever($cacheKey, function () use ($endpoint) {
            $response = (new Client())->request('GET', $endpoint, ['query' => []]);
            $body = json_decode($response->getBody(), true);

            return $this->results($body);
        });

        return compact('ip', 'content');
    }

    protected function results(array $body): array|bool
    {
        return $body['status'] == 'fail' ? false : [
            'country' => $body['country'],
            'country_code' => $body['countryCode'],
            'city' => $body['city'],
            'region' => $body['regionName'],
            'region_code' => $body['region'],
            'zip' => $body['zip'],
            'lat' => $body['lat'],
            'lon' => $body['lon'],
            'timezone' => $body['timezone'],
            'isp' => $body['org'],
            'ip' => $body['query'],
        ];
    }

    protected function validateIp($ip)
    {
        return in_array($ip, ['::1', '127.0.0.1']) ? '' : $ip;
    }
}
