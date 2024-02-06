<?php

namespace App\Tools;

use App\Models\Tool;
use Illuminate\Http\Request;
use App\Contracts\ToolInterface;


class BinaryToHex implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        return view('tools.binary-to-hex', compact('tool'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $validated = $request->validate([
            'string' => "required_without:file|max_words:{$tool->wc_tool}",
            'file' => 'nullable|mimes:txt'
        ]);

        if ($request->file) {
            $content = $request->file->getcontent();
            $content = trim($content);
        } else {
            $content = $request->string;
        }

        if (!isBinary($content)) {
            return redirect()->back()->withError(__('tools.notBinaryMsg'));
        }

        $converted_text = $this->binaryHex($content);

        $results = [
            'string' => $content,
            'converted_text' => $converted_text
        ];

        return view('tools.binary-to-hex', compact('results', 'tool'));
    }

    protected function binaryHex($bin)
    {
        $str = "";
        $char = explode(" ", $bin);
        foreach ($char as $ch) {
            $str .= dechex(bindec($ch)) . ' ';
        };

        return trim($str);
    }
}
