<?php

namespace App\Tools;

use App\Models\Tool;
use GuzzleHttp\Client;
use Iodev\Whois\Factory;
use Illuminate\Http\Request;
use GuzzleHttp\TransferStats;
use GuzzleHttp\RequestOptions;
use App\Components\ToolsManager;
use App\Contracts\ToolInterface;
use Psr\Http\Message\ResponseInterface;


class CheckGzipCompression implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        return view('tools.check-gzip-compression', compact('tool'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $validated = $request->validate([
            'domain' => 'required|fqdn',
        ]);


        $domain = $request->input('domain');
        $content = [];

        $client = new Client([
            'curl' => guzzleMozCurlOptions(),
            'timeout'  => 10.0,
            'headers'  => [
                'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36',
            ],
            'verify' => true,
            RequestOptions::ALLOW_REDIRECTS => [
                'max'             => 10,        // allow at most 10 redirects.
                'strict'          => true,      // use "strict" RFC compliant redirects.
                'referer'         => true,      // add a Referer header
                'protocols'       => ['http', 'https'],
                'track_redirects' => true,
            ],
        ]);
        $response = $client->request('GET', $domain, [
            'on_stats' => function (TransferStats $stats) use (&$content) {
                $content['pagesize_compressed'] = $stats->getHandlerStat('size_download');
            },
            'on_headers' => function (ResponseInterface $response) use (&$content) {
                // dd($response->getHeader('x-encoded-content-encoding'),$response->getHeader('content-encoding'),$response->getHeaders());
                $content['encoding'] = $response->getHeader('x-encoded-content-encoding');
            },
        ]);

        $content['status_code'] = $response->getStatusCode();
        $content['protocol'] = $response->getProtocolVersion();
        $content['headers'] = $response->getHeaders();
        $content['pagesize_uncompressed'] = mb_strlen($response->getBody(), '8bit');
        $content['saving'] = $content['pagesize_uncompressed'] - $content['pagesize_compressed'];
        $content['compression'] = round (($content['saving']/ $content['pagesize_uncompressed'] ) * 100 , 2);

        $results = [
            'domain' => $request->domain,
            'content' => $content
        ];

        return view('tools.check-gzip-compression', compact('results', 'tool'));
    }

    public static function getProperties()
    {
        $properties = ['Daily Usage'];

        return $properties;
    }

}
