<?php

namespace App\Tools;

use App\Models\Tool;
use Illuminate\Http\Request;
use App\Contracts\ToolInterface;

class CaseConverter implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        $type = 1;
        return view('tools.case-converter', compact('tool', 'type'));
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
                $converted_text  = $this->toggle_case($string);
                break;
            case ('2'):
                $c_text = strtolower($string);
                $converted_text  = ucfirst($c_text);
                break;
            case ('3'):
                $converted_text  = strtolower($string);
                break;
            case ('4'):
                $converted_text  = strtoupper($string);
                break;
            case ('5'):
                $c_text = strtolower($string);
                $converted_text  = ucwords($c_text);
                break;
            default:
        }

        $results = [
            'original_text' => $string,
            'converted_text' => $converted_text
        ];

        return view('tools.case-converter', compact('results', 'tool', 'type'));
    }

    public function toggle_case($string)
    {
        $toggle_string = '';
        $length = strlen($string);
        for ($i = 0; $i < $length; $i++) {
            if ($string[$i] >= 'A' && $string[$i] <= 'Z') {
                $toggle_string .= strtolower($string[$i]);
            } else if ($string[$i] >= 'a' && $string[$i] <= 'z') {
                $toggle_string .= strtoupper($string[$i]);
            } else {
                $toggle_string .= $string[$i];
            }
        }
        return $toggle_string;
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
}
