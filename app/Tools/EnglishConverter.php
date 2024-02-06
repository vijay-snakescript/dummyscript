<?php

namespace App\Tools;

use App\Models\Tool;
use Illuminate\Http\Request;
use App\Components\ToolsManager;
use App\Contracts\ToolInterface;

class EnglishConverter implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        $type = 1;
        return view('tools.english-converter', compact('tool', 'type'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $validated = $request->validate([
            'string' => "required|min:1|max_words:{$tool->wc_tool}",
            'type' => "required",
        ]);

        $article = $request->input('string');
        $type = $request->type;

        // parse with driver
        $driver = (new ToolsManager($tool))->driver();
        $result = $driver->parse($article, $this->get_prompt($article, $type));

        if (!$result['success']) {
            return redirect()->back()->withError($result['message']);
        }

        $results = [
            'original_text' => $article,
            'converted_text' => $result['text']
        ];

        return view('tools.english-converter', compact('results', 'tool', 'type'));
    }

    protected function get_prompt($inputText, $type)
    {
        return "Do not rewrite the text I want you to convert the below text into " . ($type == 1 ? "UK English" : "US English") . ":\n\n " . $inputText;
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
                    'options' => [['text' => "Open  AI", 'value' => "OpenAiRewriter"]],
                    'validation' => "required",
                    'type' => 'dropdown',
                    'classes' => "",
                    'dependant' => null,
                ],
                [
                    'id' => "openai_apikey",
                    'field' => "tool-options-textfield",
                    'placeholder' => "please enter api key here....",
                    'label' => "OpenAi Driver Api Key",
                    'required' => true,
                    'options' => null,
                    'validation' => "required_if:driver,OpenAiRewriter",
                    'type' => 'text',
                    'min' => null,
                    'max' => null,
                    'classes' => "",
                    'dependant' => ['settings[driver]', "OpenAiRewriter"],
                ],
            ],
            "default" => ['driver' => 'OpenAiRewriter']
        ];

        return $array;
    }
}
