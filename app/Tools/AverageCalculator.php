<?php

namespace App\Tools;

use App\Models\Tool;
use Illuminate\Http\Request;
use App\Contracts\ToolInterface;

class AverageCalculator implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        return view('tools.average-calculator', compact('tool'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $validated = $request->validate([
            'number' => 'required|min:2|array',
        ]);

        $numbers = $request->number;
        $count = count($numbers);
        $sum  = array_sum($numbers);
        $average = array_sum($numbers) / count($numbers);
        $geomatric_sum = geometric_mean($numbers);
        $harmonic_mean = harmonic_mean($numbers);
        $median = median($numbers);
        $largest = max($numbers);
        $smallest = min($numbers);
        $range = $largest - $smallest;

        $results = [
            'numbers' => $numbers,
            'count' => $count,
            'sum'  => $sum,
            'average' => $average,
            'geomatric_sum' => $geomatric_sum,
            'harmonic_mean' => $harmonic_mean,
            'median' => $median,
            'largest' => $largest,
            'smallest' => $smallest,
            'range' => $range,
        ];

        return view('tools.average-calculator', compact('results', 'tool'));
    }
}
