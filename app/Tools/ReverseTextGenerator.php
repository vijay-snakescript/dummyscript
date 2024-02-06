<?php

namespace App\Tools;

use App\Models\Tool;
use Illuminate\Http\Request;
use App\Contracts\ToolInterface;

class ReverseTextGenerator implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        $type = '1';

        return view('tools.reverse-text-generator', compact('tool', 'type'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $validated = $request->validate([
            'string' => "required|min:1|max_words:{$tool->wc_tool}",
            'type' => 'required',
        ]);

        $string = $request->input('string');
        $type = $request->input('type');

        $converted_text = "";
        switch ($type) {
            case ('1'):
                $converted_text  = $this->reverse_str($string);
                break;
            case ('2'):
                $converted_text  = $this->reverse_str($string);
                break;
            case ('3'):
                $inpStrArray = explode(" ", $string);
                $revArr = array_reverse($inpStrArray);
                $converted_text  = implode(" ", $revArr);
                break;
            case ('4'):
                $inpStrArray = explode(" ", $string);
                $revArr = array_reverse($inpStrArray);
                $converted_text  = implode(" ", $revArr);
                break;
            case ('5'):
                $converted_text  = $this->reverse_letter($string);
                break;
            default:
        }
        $results = [
            'original_text' => $string,
            'converted_text' => $converted_text
        ];

        return view('tools.reverse-text-generator', compact('results', 'tool', 'type'));
    }

    public function reverse_letter($string)
    {
        $reversed = "";
        $tmp = "";

        for ($i = 0; $i < strlen($string); $i++) {
            if ($string[$i] == " ") {
                $reversed .= $tmp . " ";
                $tmp = "";
                continue;
            }
            $tmp = set_char_encoding($string, $i) . $tmp;
        }
        $reversed .= $tmp;
        return $reversed;
    }

    public function sentence_case($string)
    {
        $sentences = preg_split('/([.?!]+)/', $string, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        $newString = '';
        foreach ($sentences as $key => $sentence) {
            $newString .= ($key & 1) == 0 ?
                ucfirst(strtolower(trim($sentence))) :
                $sentence . ' ';
        }
        return trim($newString);
    }

    public function reverse_str($string)
    {
        $length   = mb_strlen($string);
        $reversed = '';

        while ($length-- > 0) {
            $reversed .= set_char_encoding($string, $length);
        }
        return $reversed;
    }
}
