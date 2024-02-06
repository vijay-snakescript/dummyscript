<?php

namespace App\Tools;

use App\Models\Tool;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Components\ToolsManager;
use App\Contracts\ToolInterface;

class WebsiteScreenshotGenerator implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        $type = 'desktop';

        return view('tools.website-screenshot', compact('tool', 'type'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $request->validate([
            'url' => 'required|url',
        ]);

        $url = $request->input('url');
        $type = $request->input('type', 'desktop');
        $hostname = extractHostname($url, true);

        $driver = (new ToolsManager($tool))->driver();
        list($success, $image) = $driver->parse($request);

        if (!$success) {
            return redirect()->back()->withError($image);
        }

        $results = [
            'image' => url($image),
            'filename' => Str::of($hostname)->replace('.', ' ')->slug()->finish('.png')->toString()
        ];

        return view('tools.website-screenshot', compact('tool', 'results', 'url', 'type'));
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
