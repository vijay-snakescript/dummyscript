<?php

namespace App\Tools;

use App\Models\Tool;
use Illuminate\Http\Request;
use App\Contracts\ToolInterface;
use App\Helpers\Classes\JsMinifier;

class JavascriptMinify implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        return view('tools.javascript-minify', compact('tool'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $validated = $request->validate([
            'javaScript' => 'required'
        ]);

        $script = $request->input('javaScript');
        $content = JsMinifier::minify($script, ['flaggedComments' => false]);
        $input_size = mb_strlen($script, '8bit');
        $out_size = mb_strlen($content, '8bit');
        $save_size = 100 - (($out_size / $input_size) * 100);

        $results = [
            'javaScript' => $script,
            'content' => $content,
            'input_size' => $input_size,
            'output_size' => $out_size,
            'save_size' => round($save_size, 2)
        ];

        return view('tools.javascript-minify', compact('results', 'tool'));
    }
}
