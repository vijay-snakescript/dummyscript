<?php

namespace App\Components\Drivers;

use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use App\Contracts\ToolDriverInterface;

class FreeIpApi implements ToolDriverInterface
{
    protected $endpoint = "https://freeipapi.com/api/json/";

    public function parse($ip)
    {
        // $ip = $this->validateIp($ip);
        $endpoint = $this->endpoint . $ip;
        $cacheKey = (empty($ip) ? (string) Str::uuid() : $ip)  . '-ip';
        $content = Cache::rememberForever($cacheKey, function () use ($endpoint) {
            $response = (new Client())->request('GET', $endpoint, ['query' => []]);
            $body = json_decode($response->getBody(), true);

            return $this->results($body);
        });

        return compact('ip', 'content');
    }

    protected function results(array $body)
    {
        return empty($body['latitude']) || $body['latitude'] == 0 ? false : [
            'country' => $body['countryName'],
            'country_code' => $body['countryCode'],
            'city' => $body['cityName'],
            'region' => $body['regionName'],
            'region_code' => null,
            'zip' => $body['zipCode'],
            'lat' => $body['latitude'],
            'lon' => $body['longitude'],
            'timezone' => $body['timeZone'],
            'isp' => null,
            'ip' => $body['ipAddress'],
        ];
    }

    protected function validateIp($ip)
    {
        return in_array($ip, ['::1', '127.0.0.1']) ? '' : $ip;
    }
}
