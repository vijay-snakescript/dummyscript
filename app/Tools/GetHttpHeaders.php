<?php

namespace App\Tools;

use App\Models\Tool;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Contracts\ToolInterface;
use Psr\Http\Message\ResponseInterface;

class GetHttpHeaders implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        return view('tools.get-http-headers', compact('tool'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $validated = $request->validate([
            'domain' => 'required|fqdn',
        ]);

        $domain = $request->input('domain');
        $content = [];

        $client = new Client();
        $response = $client->request('GET', $domain, [
            'on_headers' => function (ResponseInterface $response) {
                $servers = array_filter($response->getHeader('server'), function ($value) {
                    return !in_array($value, ['amazon', 'cloudflare', 'gws', 'Server', 'Apple', 'tsa_o', 'ATS']);
                });
                $content['hsts'] = count($response->getHeader('Strict-Transport-Security')) !== 0;
                $content['servers'] = $servers;
                $content['encoding'] = $response->getHeader('x-encoded-content-encoding');
            },
        ]);
        $content = $response->getHeaders() ?? null;

        $results = [
            'domain' => $request->domain,
            'content' => $content
        ];

        return view('tools.get-http-headers', compact('results', 'tool'));
    }
}
