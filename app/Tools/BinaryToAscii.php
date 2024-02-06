<?php

namespace App\Tools;

use App\Models\Tool;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Contracts\ToolInterface;


class BinaryToAscii implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        return view('tools.binary-to-ascii', compact('tool'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $validated = $request->validate([
            'string' => "required_without:file|max_words:{$tool->wc_tool}",
            'file' => 'nullable|mimes:txt'
        ]);

        if ($request->file) {
            $content = $request->file->getcontent();
            $content = Str::of($content)->trim()->toString();
        } else {
            $content = Str::of($request->string)->trim()->toString();
        }

        if (!isBinary($content)) {
            return redirect()->back()->withError(__('tools.notBinaryMsg'));
        }

        $converted_text = $this->binToAscii($content);
        $results = [
            'string' => $content,
            'converted_text' => $converted_text
        ];

        return view('tools.binary-to-ascii', compact('results', 'tool'));
    }

    function binToAscii($bin)
    {
        $text = array();
        $bin = explode(' ', $bin);
        for ($i = 0; count($bin) > $i; $i++) {
            $text[] = chr(bindec($bin[$i]));
        }

        return implode($text);
    }
}
