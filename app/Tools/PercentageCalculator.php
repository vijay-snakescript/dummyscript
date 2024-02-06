<?php

namespace App\Tools;

use App\Models\Tool;
use Illuminate\Http\Request;
use App\Contracts\ToolInterface;


class PercentageCalculator implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        return view('tools.percentage-calculator', compact('tool'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $validated = $request->validate([
            'first' => 'required|numeric|gt:0',
            'second' => 'required|numeric|gt:0',
        ]);

        switch ($request->formula) {
            case '1':
                $result = $this->percentageOfNo($request->first, $request->second);
                break;
            case '2':
                $result = $this->calculatePercentage($request->first, $request->second);
                break;
            case '3':
                $result = $this->yIsPOfWhat($request->first, $request->second);
                break;
            case '4':
                $result = $this->percentOfXisY($request->first, $request->second);
                break;
            case '5':
                $result = $this->percentOfWhatisY($request->first, $request->second);
                break;
            case '6':
                $result = $this->percentOfXisWhat($request->first, $request->second);
                break;
            case '7':
                $result = $this->outOfWhatIsPercentage($request->first, $request->second);
                break;
            case '8':
                $result = $this->whatOutOfXIsP($request->first, $request->second);
                break;
            case '9':
                $result = $this->yOutOfXisWhat($request->first, $request->second);
                break;
            case '10':
                $result = $this->xPlusPpercentIsWhat($request->first, $request->second);
                break;
            case '11':
                $result = $this->xPlusWhatPercentisY($request->first, $request->second);
                break;
            case '12':
                $result = $this->whatPlusPpercentIsY($request->first, $request->second);
                break;
            case '13':
                $result = $this->xMinusPercenageisWhat($request->first, $request->second);
                break;
            case '14':
                $result = $this->XminusWhatisPercentage($request->first, $request->second);
                break;
            case '15':
                $result = $this->whatMinusPercentisY($request->first, $request->second);
                break;

            default:
                break;
        }
        $results = [
            'solution' => $result,
            'first' => $request->first,
            'second' => $request->second,
            'formula' => $request->formula,
        ];
        return view('tools.percentage-calculator', compact('results', 'tool'));
    }

    private function percentageOfNo($first, $second)
    {
        $result = [];
        $result['calculation'] = ($first / 100) * $second;
        $final = $result['calculation'];
        $result['equation'] = __('tools.percentageOfNoEQ', compact("first", "second", "final"));

        return $result;
    }

    private function calculatePercentage($first, $second)
    {
        $result = [];
        $div = $first / $second;
        $result['calculation'] = ($first / $second) * 100;
        $final = $result['calculation'];
        $result['equation'] = __('tools.calculatePercentageEQ', compact("first", "second", "final", "div"));
        return $result;
    }

    private function yIsPOfWhat($first, $second)
    {
        $result = [];
        $div = $second / 100;
        $result['calculation'] = $first / $div;
        $final = $result['calculation'];
        $result['equation'] = __('tools.yIsPOfWhatEQ', compact("first", "second", "final", "div"));
        return $result;
    }

    private function percentOfXisY($first, $second)
    {
        $result = [];
        $div = $second / $first;
        $result['calculation'] = ($second / $first) * 100;
        $final = $result['calculation'];
        $result['equation'] = __('tools.percentOfXisYEQ', compact("first", "second", "final", "div"));
        return $result;
    }

    private function percentOfWhatisY($first, $second)
    {
        $result = [];
        $div = $first / 100;
        $result['calculation'] = $second / $div;
        $final = $result['calculation'];
        $result['equation'] = __('tools.percentOfWhatisYEQ', compact("first", "second", "final", "div"));
        return $result;
    }

    private function percentOfXisWhat($first, $second)
    {
        $result = [];
        $div = $first / 100;
        $result['calculation'] = $div * $second;
        $final = $result['calculation'];
        $result['equation'] = __('tools.percentOfXisWhatEQ', compact("first", "second", "final", "div"));
        return $result;
    }

    private function outOfWhatIsPercentage($first, $second)
    {
        $result = [];
        $div = $second / 100;
        $result['calculation'] = $first / $div;
        $final = $result['calculation'];
        $result['equation'] = __('tools.outOfWhatIsPercentageEQ', compact("first", "second", "final", "div"));
        return $result;
    }

    private function whatOutOfXIsP($first, $second)
    {
        $result = [];
        $div = $second / 100;
        $result['calculation'] = $first * $div;
        $final = $result['calculation'];
        $result['equation'] = __('tools.whatOutOfXIsPEQ', compact("first", "second", "final", "div"));
        return $result;
    }

    private function yOutOfXisWhat($first, $second)
    {
        $result = [];
        $div = $first / $second;
        $result['calculation'] = $div * 100;
        $final = $result['calculation'];
        $result['equation'] = __('tools.yOutOfXisWhatEQ', compact("first", "second", "final", "div"));

        return $result;
    }

    private function xPlusPpercentIsWhat($first, $second)
    {
        $result = [];
        $div = $second / 100;
        $result['calculation'] = $first * (1 + $div);
        $final = $result['calculation'];
        $result['equation'] = __('tools.xPlusPpercentIsWhatEQ', compact("first", "second", "final", "div"));
        return $result;
    }

    private function xPlusWhatPercentisY($first, $second)
    {
        $result = [];
        $div = ($second / $first) - 1;
        $result['calculation'] = $div * 100;
        $final = $result['calculation'];
        $result['equation'] = __('tools.xPlusWhatPercentisYEQ', compact("first", "second", "final", "div"));
        return $result;
    }

    private function whatPlusPpercentIsY($first, $second)
    {
        $result = [];
        $div = $first / 100;
        $sum = 1 + $div;
        $result['calculation'] = $second / $sum;
        $final = $result['calculation'];
        $result['equation'] = __('tools.whatPlusPpercentIsYEQ', compact("first", "second", "final", "div", "sum"));
        return $result;
    }

    private function xMinusPercenageisWhat($first, $second)
    {
        $result = [];
        $div = $second / 100;
        $result['calculation'] = $first * (1 - $div);
        $final = $result['calculation'];
        $result['equation'] = __('tools.xMinusPisWhatEQ', compact("first", "second", "final", "div"));
        return $result;
    }

    private function XminusWhatisPercentage($first, $second)
    {
        $result = [];
        $div = 1 - ($second / $first);
        $result['calculation'] = $div * 100;
        $final = $result['calculation'];
        $result['equation'] = __('tools.XminusWhatisPEQ', compact("first", "second", "final", "div"));
        return $result;
    }

    private function whatMinusPercentisY($first, $second)
    {
        $result = [];
        $div = $first / 100;
        $result['calculation'] = $second / (1 - $div);
        $final = $result['calculation'];
        $result['equation'] = __('tools.whatMinusPisYEQ', compact("first", "second", "final", "div"));
        return $result;
    }
}
