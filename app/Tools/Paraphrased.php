<?php

namespace App\Tools;

use App\Models\Tool;
use Illuminate\Http\Request;
use App\Components\ToolsManager;
use App\Contracts\ToolInterface;

class Paraphrased implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        return view('tools.paraphrased', compact('tool'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $validated = $request->validate([
            'string' => "required|min:1|max_words:{$tool->wc_tool}",
        ]);

        $article = $request->input('string');
        $driver = (new ToolsManager($tool))->driver();
        $result = $driver->parse($article, $this->get_prompt($article));

        if (!$result['success']) {
            return redirect()->back()->withError($result['message']);
        }

        $results = [
            'original_text' => $article,
            'converted_text' => $result['text']
        ];

        return view('tools.paraphrased', compact('results', 'tool'));
    }


    protected function get_prompt($inputText)
    {
        return "Paraphrase the below text:\n\n" . $inputText;
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
