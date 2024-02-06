<?php

namespace App\Tools;

use Carbon\Carbon;
use App\Models\Tool;
use GuzzleHttp\Client;
use Iodev\Whois\Factory;
use Illuminate\Http\Request;
use GuzzleHttp\TransferStats;
use GuzzleHttp\RequestOptions;
use App\Components\ToolsManager;
use App\Contracts\ToolInterface;
use App\Helpers\Classes\CheckSSL;
use App\Helpers\Classes\SEOAnalyzer;
use Psr\Http\Message\ResponseInterface;


class SpiderSimulator implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        return view('tools.spider-simulator', compact('tool'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $validated = $request->validate([
            'domain' => 'required|fqdn',
        ]);


        $domain = $request->input('domain');

        $analyzer = new SEOAnalyzer();
        $result = $analyzer->analyzeSimulator($domain);

        $results = [
            'domain' => $request->domain,
            'result' => $result
        ];

        return view('tools.spider-simulator', compact('results', 'tool'));
    }

    public static function getProperties()
    {
        $properties = ['Daily Usage'];

        return $properties;
    }
}
