<?php

namespace App\Components\Drivers;

use Exception;
use App\Models\Tool;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use App\Contracts\ToolDriverInterface;

class MozApiDriver implements ToolDriverInterface
{
    private $tool;
    private $domain;

    public function __construct(Tool $tool)
    {
        $this->tool = $tool;
    }

    public function parse($domain)
    {
        $this->domain = $domain;
        $cacheKey = empty($domain) ? (string) Str::uuid() : Str::slug($domain);
        try {
            if (!isset($this->tool->settings->moz_accessid) || !isset($this->tool->settings->moz_secretKey) || empty($this->tool->settings->moz_accessid) || empty($this->tool->settings->moz_secretKey)) {
                $message = __('common.apiKeyNotProvided');

                return compact('domain', 'message');
            }

            $content = Cache::remember($cacheKey, 3600, function () use ($domain) {
                // Get your access id and secret key here: https://moz.com/products/api/keys
                $accessID  = $this->tool->settings->moz_accessid ?? '';
                $secretKey = $this->tool->settings->moz_secretKey ?? '';
                $expires = time() + 300;
                $stringToSign = $accessID . "\n" . $expires;
                $binarySignature = hash_hmac('sha1', $stringToSign, $secretKey, true);
                $urlSafeSignature = urlencode(base64_encode($binarySignature));
                $cols = "103079233568";
                $endpoint = "http://lsapi.seomoz.com/linkscape/url-metrics/" . urlencode($domain) . "?Cols=" . $cols . "&AccessID=" . $accessID . "&Expires=" . $expires . "&Signature=" . $urlSafeSignature;
                $client = new Client();
                $response = $client->request('GET', $endpoint);
                $json = $response->getBody()->getContents();
                $info = json_decode($json, TRUE);

                $content = $this->results($info);

                return $content;
            });

            return compact('domain', 'content');
        } catch (Exception $e) {
            $message = __('common.somethingWentWrong');

            return compact('domain', 'message');
        }
    }

    protected function results($info)
    {
        return  [
            'ip' => $this->domain,
            'da' => $info['pda'] ?? 0,
            'pa' => $info['upa'] ?? 0,
            'mr' => $info['umrp'] ?? $info['umrr'] ?? 0,
            'linking' => format_number($info['ueid'] ?? 0),
        ];
    }
}
