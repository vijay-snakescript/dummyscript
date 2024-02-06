<?php

namespace App\Tools;

use App\Models\Tool;
use Illuminate\Http\Request;
use App\Components\ToolsManager;
use App\Contracts\ToolInterface;
use App\Rules\MultipleIpsValidator;
use App\Rules\MultipleMaxlinesValidator;

class IpLocation implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        return view('tools.ip-location', compact('tool'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $validated = $request->validate([
            'ip' => [
                'required', new MultipleIpsValidator, new MultipleMaxlinesValidator($tool->no_domain_tool)
            ],
        ]);
        $results = ['ip' => $request->ip, 'ipAddresses' => json_encode(explode(PHP_EOL, $request->ip))];

        return view('tools.ip-location', compact('results', 'tool'));
    }

    public function postAction(Request $request, $tool)
    {
        $ip = $request->input('ip');
        $driver = (new ToolsManager($tool))->driver();
        $results = $driver->parse($ip);
        $content = $results['content'] ?? false;

        return $content;
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
                    'options' => [['text' => "IP Api", 'value' => "IpApi"], ['text' => "Free IP API", 'value' => "FreeIpApi"]],
                    'validation' => "required",
                    'type' => 'dropdown',
                    'classes' => "",
                    'dependant' => null,
                ]
            ],
            "default" => ['driver' => 'IpApi']
        ];

        return $array;
    }
}
