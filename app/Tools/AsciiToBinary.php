<?php

namespace App\Tools;

use App\Models\Tool;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Contracts\ToolInterface;

class AsciiToBinary implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        return view('tools.ascii-to-binary', compact('tool'));
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
            $content = $request->string;
        }

        $converted_text = $this->binaryEncode($content);
        $results = [
            'string' => $content,
            'converted_text' => $converted_text
        ];

        return view('tools.ascii-to-binary', compact('results', 'tool'));
    }

    public function binaryEncode($str)
    {
        $bin = (string)"";
        $prep = (string)"";
        for ($i = 0; $i < strlen($str); $i++) {
            $bincur = decbin(ord($str[$i]));
            $binlen = strlen($bincur);
            if ($binlen < 8) {
                for ($j = 8; $j > $binlen; $binlen++) {
                    $prep .= "";
                }
            }
            $bin .= $prep . $bincur . " ";
            $prep = "";
        }

        return substr($bin, 0, strlen($bin) - 1);
    }
}
