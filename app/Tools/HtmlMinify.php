<?php

namespace App\Tools;

use App\Models\Tool;
use Illuminate\Http\Request;
use App\Contracts\ToolInterface;
use App\Helpers\Classes\Minifer\HTMLMinify as Minifer;

class HtmlMinify implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        return view('tools.html-minify', compact('tool'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $validated = $request->validate([
            'html' => 'required'
        ]);

        $html = $request->input('html');
        $content = Minifer::minify($html, ['optimizationLevel' => 1, 'removeDuplicateAttribute' => true, 'emptyElementAddWhitespaceBeforeSlash' => true]);
        $input_size = mb_strlen($html, '8bit');
        $out_size = mb_strlen($content, '8bit');
        $save_size = 100 - (($out_size / $input_size) * 100);

        $results = [
            'html' => $html,
            'content' => $content,
            'input_size' => $input_size,
            'output_size' => $out_size,
            'save_size' => round($save_size, 2)
        ];

        return view('tools.html-minify', compact('results', 'tool'));
    }
}
