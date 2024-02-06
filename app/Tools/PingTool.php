<?php

namespace App\Tools;

use App\Models\Tool;
use Illuminate\Http\Request;
use App\Helpers\Classes\Ping;
use App\Contracts\ToolInterface;

class PingTool implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        return view('tools.ping-tool', compact('tool'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $validated = $request->validate([
            'domain' => 'required|fqdn'
        ]);
        $results = ['domain' => $request->domain];

        return view('tools.ping-tool', compact('results', 'tool'));
    }

    public function postAction(Request $request, $tool)
    {
        $domain = $request->input('domain');
        $domain = parse_url($request->domain);
        $ping = new Ping($domain['host']);

        $results = [];
        $results['time'] = $ping->ping();
        $results['ip'] = $ping->getIpAddress();
        $results['ttl'] = $ping->getTtl();
        $content = $results ?? false;

        return $content;
    }
}
