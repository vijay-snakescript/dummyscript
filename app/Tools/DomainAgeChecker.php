<?php

namespace App\Tools;

use App\Models\Tool;
use Illuminate\Http\Request;
use App\Components\ToolsManager;
use App\Contracts\ToolInterface;
use App\Rules\MultipleDomainsValidator;
use App\Rules\MultipleMaxlinesValidator;


class DomainAgeChecker implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        return view('tools.domain-age-checker', compact('tool'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $validated = $request->validate(['domain' => ['required', new MultipleDomainsValidator, new MultipleMaxlinesValidator($tool->no_domain_tool)]]);

        $results = ['domain' => $request->domain, 'domainAddresses' => fqdnList($request->domain)];

        return view('tools.domain-age-checker', compact('results', 'tool'));
    }

    public function postAction(Request $request, $tool)
    {
        $domain = $request->input('domain');
        $driver = (new ToolsManager($tool))->driver();
        $results = $driver->parse($domain);
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
