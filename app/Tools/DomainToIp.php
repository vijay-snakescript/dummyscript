<?php

namespace App\Tools;

use App\Models\Tool;
use Illuminate\Http\Request;
use App\Components\ToolsManager;
use App\Contracts\ToolInterface;

class DomainToIp implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        return view('tools.domain-to-ip', compact('tool'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $validated = $request->validate([
            'domain' => 'required|fqdn',
        ]);

        $url = extractHostname($request->domain);
        $driver = (new ToolsManager($tool))->channel("ipApi");
        $content = $driver->parse($url);
        $results = [
            'domain' => $request->domain,
            'content' => $content
        ];

        return view('tools.domain-to-ip', compact('results', 'tool'));
    }

    public static function getFileds()
    {
        $array = [
            'title' => "Drivers",
            'fields' => [
                [
                    'id' => "driver",
                    'field' => "tool-options-select",
                    'placeholder' => "Driver",
                    'label' => "Driver",
                    'required' => true,
                    'options' => [['text' => "Default", 'value' => "WhoisDomainChecker"]],
                    'validation' => "required",
                    'type' => 'dropdown',
                    'classes' => "",
                    'dependant' => null,
                ],
            ],
            "default" => ['driver' => 'WhoisDomainChecker']
        ];

        return $array;
    }
}
