<?php

namespace App\Tools;

use App\Models\Tool;
use Illuminate\Http\Request;
use App\Contracts\ToolInterface;

class AdsenseCalculator implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        return view('tools.adsense-calculator', compact('tool'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $validated = $request->validate([
            'daily_impression' => 'required',
            'ctr' => 'required',
            'cost' => 'required',
        ]);

        $clicks_per_day = (($request->daily_impression / 100) * $request->ctr);
        $clicks_per_month = $clicks_per_day * 30;
        $clicks_per_year = $clicks_per_day * 365;
        $earning_per_day = $clicks_per_day * $request->cost;
        $earning_per_month = 30 * $earning_per_day;
        $earning_per_year = 365 * $earning_per_day;
        $results = [
            'daily_impression' => $request->daily_impression,
            'ctr' => $request->ctr,
            'cost' => $request->cost,
            'earning_per_day' => $earning_per_day,
            'earning_per_month' => $earning_per_month,
            'earning_per_year' => $earning_per_year,
            'clicks_per_day' => $clicks_per_day,
            'clicks_per_month' => $clicks_per_month,
            'clicks_per_year' => $clicks_per_year
        ];

        return view('tools.adsense-calculator', compact('results', 'tool'));
    }
}
