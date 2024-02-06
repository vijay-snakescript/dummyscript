<?php

namespace App\Tools;

use App\Models\Tool;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Contracts\ToolInterface;


class BinaryToText implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        return view('tools.binary-to-text', compact('tool'));
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

        $converted_text = $this->binaryDecode($content);
        $results = [
            'string' => $content,
            'converted_text' => $converted_text
        ];

        return view('tools.binary-to-text', compact('results', 'tool'));
    }

    protected function binaryDecode($bin)
    {
        $str = "";
        $char = explode(" ", $bin);
        foreach ($char as $ch) $str .= chr(bindec($ch));

        return trim($str);
    }
}
