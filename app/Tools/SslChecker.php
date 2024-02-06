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
use Psr\Http\Message\ResponseInterface;


class SslChecker implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        return view('tools.ssl-checker', compact('tool'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $validated = $request->validate([
            'domain' => 'required|fqdn',
        ]);


        $domain = $request->input('domain');

        $ssl = new CheckSSL([$domain]);
        $content = $ssl->check();

        $content['parse_result'] = $this->parseResult($content);
        ;

        $results = [
            'domain' => $request->domain,
            'content' => $content
        ];

        return view('tools.ssl-checker', compact('results', 'tool'));
    }

    private function parseResult($data)
    {
        $result = [];
        $result['server_from_date'] = Carbon::parse($data['certInfo']['validFrom_time_t'])->format(setting('date_format'));
        $result['server_to_date'] = Carbon::parse($data['certInfo']['validTo_time_t'])->format(setting('date_format'));

        $sans = $data['certInfo']['extensions']['subjectAltName'];
        $result['sans'] = str_replace("DNS:", "", $sans);
        return $result;
    }

    public static function getProperties()
    {
        $properties = ['Daily Usage'];

        return $properties;
    }
}
