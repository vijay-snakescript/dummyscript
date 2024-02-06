<?php

namespace App\Tools;

use App\Models\Tool;
use Illuminate\Http\Request;
use App\Contracts\ToolInterface;


class DecimalToHex implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        return view('tools.decimal-to-hex', compact('tool'));
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

        if (!isDecimal($content)) {
            return redirect()->back()->withError(__('tools.notDecimalMsg'));
        }

        $converted_text = $this->DecimalToHex($content);
        $results = [
            'string' => $content,
            'converted_text' => $converted_text
        ];

        return view('tools.decimal-to-hex', compact('results', 'tool'));
    }

    protected function DecimalToHex($string)
    {
        $str = '';
        $char = explode(" ", $string);
        foreach ($char as $ch) {
            $str .= dechex($ch) . ' ';
        }

        return trim($str);
    }
}
