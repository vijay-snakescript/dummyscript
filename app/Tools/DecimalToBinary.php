<?php

namespace App\Tools;

use App\Models\Tool;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Contracts\ToolInterface;


class DecimalToBinary implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        return view('tools.decimal-to-binary', compact('tool'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $validated = $request->validate([
            'string' => "required_without:file|max_words:{$tool->wc_tool}",
            'file' => 'nullable|mimes:txt'
        ]);

        if ($request->file) {
            $content = $request->file->get();
            $content = Str::of($content)->trim()->toString();
        } else {
            $content = Str::of($request->string)->trim()->toString();
        }

        if (!isDecimal($content)) {
            return redirect()->back()->withError(__('tools.notDecimalMsg'));
        }

        $converted_text = $this->DecimalToBinary($content);

        $results = [
            'string' => $content,
            'converted_text' => $converted_text
        ];

        return view('tools.decimal-to-binary', compact('results', 'tool'));
    }

    protected function DecimalToBinary($string)
    {
        $str = '';
        $char = explode(" ", $string);
        foreach ($char as $ch) {
            $str .= decbin($ch) . ' ';
        }

        return trim($str);
    }
}
