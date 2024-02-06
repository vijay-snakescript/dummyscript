<?php

namespace App\Tools;

use App\Models\Tool;
use Illuminate\Http\Request;
use App\Contracts\ToolInterface;

class WordCombiner implements ToolInterface
{
    private $prefix = null;
    private $postfix = null;
    private $seperator = null;
    private $wrap = null;

    public function render(Request $request, Tool $tool)
    {
        return view('tools.word-combiner', compact('tool'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $validated = $request->validate([
            'string_first' => 'required|min:1|max:255',
            'string_second' => 'required_without:string_third|max:255',
            'string_third' => 'required_without:string_second|max:255',
        ]);

        $this->prefix = $request->input('pre-phase');
        $this->postfix = $request->input('post-phase');
        $this->seperator = $request->input('seperator');
        $this->wrap = $request->input('wrap-in');
        $mergedWords = $this->mergeAllWords($request);

        $results = [
            'string_first' => $request->string_first,
            'string_second' => $request->string_second,
            'string_third' => $request->string_third,
            'pre_phrase' => $this->prefix,
            'post_phrase' => $this->postfix,
            'seperator' => $this->seperator,
            'wrap_in' => $this->wrap,
            'merged_total' => count($mergedWords),
            'converted_text' => implode(PHP_EOL, $mergedWords)
        ];

        return view('tools.word-combiner', compact('results', 'tool'));
    }

    private function mergeAllWords(Request $request)
    {
        $string_first = $request->input('string_first');
        $string_second = $request->input('string_second');
        $string_third = $request->input('string_third');

        $words_1 = array_values(array_filter(explode(PHP_EOL, $string_first)));
        $words_2 = array_values(array_filter(explode(PHP_EOL, $string_second)));
        $words_3 = array_values(array_filter(explode(PHP_EOL, $string_third)));
        $results = collect([...$words_1, ...$words_2, ...$words_3])->map(function ($word) {
            return $this->wrapWord($this->setPhrase($word));
        });

        foreach ($words_1 as $first) {
            foreach ($words_2 as $second) {
                $word = $this->wrapWord($this->setPhrase($first . $this->seperator . $second));
                $results->push($word);
                foreach ($words_3 as $third) {
                    $word = $this->wrapWord($this->setPhrase($first . $this->seperator . $second . $this->seperator . $third));
                    $results->push($word);
                }
            }
            foreach ($words_3 as $third) {
                $word = $this->wrapWord($this->setPhrase($first . $this->seperator . $third));
                $results->push($word);
            }
        }
        foreach ($words_2 as $second) {
            foreach ($words_3 as $third) {
                $word = $this->wrapWord($this->setPhrase($second . $this->seperator . $third));
                $results->push($word);
            }
        }

        return $results->toArray();
    }

    private function setPhrase($string)
    {
        $phrase = '';
        if (!empty($this->prefix)) {
            $phrase = $this->prefix;
        }

        $phrase .= $string;
        if (!empty($this->postfix)) {
            $phrase .= $this->postfix;
        }

        return $phrase;
    }

    private function wrapWord($word)
    {
        switch ($this->wrap) {
            case ('1'):
                $word  = $word;
                break;
            case ('2'):
                $word  = "(" . $word . ")";
                break;
            case ('3'):
                $word  = '"' . $word . '"';
                break;
            case ('4'):
                $word  = "'" . $word . "'";
                break;
            case ('5'):
                $word  = "[" . $word . "]";
                break;
        }

        return $word;
    }
}
