<?php

namespace App\Tools;

use App\Models\Tool;
use Illuminate\Http\Request;
use App\Components\ToolsManager;
use App\Contracts\ToolInterface;
use App\Rules\MultipleDomainsValidator;
use App\Rules\MultipleMaxlinesValidator;
use Exception;

class DomainAuthorityChecker implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        return view('tools.domain-authority-checker', compact('tool'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $validated = $request->validate([
            'domain' => [
                'required', new MultipleDomainsValidator, new MultipleMaxlinesValidator($tool->no_domain_tool)
            ],
        ]);
        $results = ['domain' => $request->domain, 'domainAddresses' => json_encode(explode(PHP_EOL, $request->domain))];

        return view('tools.domain-authority-checker', compact('results', 'tool'));
    }

    public function postAction(Request $request, $tool)
    {
        try {
            $domain = $request->input('domain');
            $driver = (new ToolsManager($tool))->driver();
            $results = $driver->parse($domain);
            $content = $results['content'] ?? $results['message'];
        } catch (Exception $e) {
            return ['success' => false, 'content' => $e->getMessage()];
        }

        return ['success' => isset($results['content']) ? true : false, 'content' => $content];
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
                    'options' => [['text' => "Default", 'value' => "mozApi"]],
                    'validation' => "required",
                    'type' => 'dropdown',
                    'classes' => "",
                    'dependant' => null,
                ],
                [
                    'id' => "moz_accessid",
                    'field' => "tool-options-textfield",
                    'placeholder' => "please enter access id here....",
                    'label' => "MozApi Driver Access ID",
                    'required' => true,
                    'options' => null,
                    'validation' => "required_if:driver,mozApi",
                    'type' => 'text',
                    'min' => null,
                    'max' => null,
                    'classes' => "",
                    'dependant' => ['settings[driver]', "mozApi"],
                ],
                [
                    'id' => "moz_secretKey",
                    'field' => "tool-options-textfield",
                    'placeholder' => "please enter secret key here....",
                    'label' => "MozApi Secret Key",
                    'required' => true,
                    'options' => null,
                    'validation' => "required_if:driver,mozApi",
                    'type' => 'text',
                    'min' => null,
                    'max' => null,
                    'classes' => "",
                    'dependant' => ['settings[driver]', "mozApi"],
                ],
            ],
            "default" => ['driver' => 'mozApi']
        ];

        return $array;
    }
}
