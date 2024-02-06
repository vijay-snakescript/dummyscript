<?php

namespace App\Tools;

use App\Models\Tool;
use Illuminate\Http\Request;
use App\Contracts\ToolInterface;


class TextToAscii implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        return view('tools.text-to-ascii', compact('tool'));
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
        $converted = unpack("C*", $content);
        $converted_text = "";
        foreach ($converted as $con) {
            $converted_text .= " " . $con;
        }
        $results = [
            'string' => $content,
            'converted_text' => $converted_text
        ];

        return view('tools.text-to-ascii', compact('results', 'tool'));
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
