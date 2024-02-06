<?php

namespace App\Tools;

use App\Models\Tool;
use Iodev\Whois\Factory;
use Illuminate\Http\Request;
use App\Components\ToolsManager;
use App\Contracts\ToolInterface;
use App\Helpers\Classes\SEOAnalyzer;

class MobileFriendlyTest implements ToolInterface
{

    public function render(Request $request, Tool $tool)
    {
        return view('tools.mobile-friendly-test', compact('tool'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $validated = $request->validate([
            'url' => 'required|fqdn',
        ]);

        $analyzer = new SEOAnalyzer();
        $usableSource = $analyzer->getPageContent($request->url);
        $document = $analyzer->parseHtml($usableSource);
        $headNode = $document->getElementsByTagName('head')->item(0);
        $viewport = $analyzer->getViewport($headNode);


        // dd($viewport);

        $results = [
            'url' => $request->url,
            'baseUrl' => parse_url($request->url, PHP_URL_SCHEME) . '://' . parse_url($request->url, PHP_URL_HOST) . '/' . ltrim(parse_url($request->url, PHP_URL_PATH), '/'),
            'mobileFriendly' => !empty($viewport) ? true : false
        ];

        return view('tools.mobile-friendly-test', compact('results', 'tool'));
    }

    public static function getProperties()
    {
        $properties = ['Daily Usage'];

        return $properties;
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
                    'options' => [['text' => "Phantom Js", 'value' => "DefaultScreenshot"], ['text' => "Thum.io", 'value' => "thumioScreenshot"]],
                    'validation' => "required",
                    'type' => 'dropdown',
                    'classes' => "",
                    'dependant' => null,
                ],
                [
                    'id' => "screenshot_thumio_api_key",
                    'field' => "tool-options-textfield",
                    'placeholder' => "Please enter thum.ip API key",
                    'label' => "Thum.io API Key",
                    'required' => false,
                    'options' => null,
                    'validation' => "nullable",
                    'type' => 'text',
                    'min' => null,
                    'max' => null,
                    'classes' => "",
                    'dependant' => ['settings[driver]', "thumioScreenshot"],
                ],
                [
                    'id' => "phantomjs_node_module_path",
                    'field' => "tool-options-textfield",
                    'placeholder' => "please enter node module path here....",
                    'label' => "Node Module Path",
                    'required' => true,
                    'options' => null,
                    'validation' => "nullable",
                    'type' => 'text',
                    'min' => null,
                    'max' => null,
                    'classes' => "",
                    'dependant' => ['settings[driver]', "DefaultScreenshot"],
                ],
                [
                    'id' => "phantomjs_npm_path",
                    'field' => "tool-options-textfield",
                    'placeholder' => "please enter npm path here....",
                    'label' => "NPM Path",
                    'required' => true,
                    'options' => null,
                    'validation' => "nullable",
                    'type' => 'text',
                    'min' => null,
                    'max' => null,
                    'classes' => "",
                    'dependant' => ['settings[driver]', "DefaultScreenshot"],
                ],
                [
                    'id' => "phantomjs_chrome_path",
                    'field' => "tool-options-textfield",
                    'placeholder' => "please enter chrome path here....",
                    'label' => "Chrome Path",
                    'required' => true,
                    'options' => null,
                    'validation' => "nullable",
                    'type' => 'text',
                    'min' => null,
                    'max' => null,
                    'classes' => "",
                    'dependant' => ['settings[driver]', "DefaultScreenshot"],
                ],
                [
                    'id' => "phantomjs_tool_agent",
                    'field' => "tool-options-textfield",
                    'placeholder' => "please enter tool agent here....",
                    'label' => "Tool Agent",
                    'required' => true,
                    'options' => null,
                    'validation' => "nullable",
                    'type' => 'text',
                    'min' => null,
                    'max' => null,
                    'classes' => "",
                    'dependant' => ['settings[driver]', "DefaultScreenshot"],
                ],

            ],
            "default" => [
                'driver' => 'DefaultScreenshot', 'phantomjs_tool_agent' => '', 'phantomjs_chrome_path' => '', 'phantomjs_npm_path' => '', 'phantomjs_node_module_path' => ''
            ]
        ];

        return $array;
    }

}
