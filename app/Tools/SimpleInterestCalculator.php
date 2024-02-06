<?php

namespace App\Tools;

use Carbon\Carbon;
use App\Models\Tool;
use Illuminate\Http\Request;
use App\Contracts\ToolInterface;

class SimpleInterestCalculator implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        return view('tools.simple-interest-calculator', compact('tool'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $startDate = $request->start_date ?? Carbon::now();
        $startingBalance = $request->starting_balance;
        $years = $request->year;

        if ($request->type == "date") {
            $years = Carbon::parse($request->end_date)->age;
        }

        $months = ($years * 12);
        if ($request->period == "yearly") {
            $insterestRate = $request->insterest_rate / 100;
            $finalBalance  = $startingBalance * (1 + ($insterestRate * $months));
            $interestAccrued = $finalBalance - $startingBalance;
            $monthlyIntrest =  $interestAccrued / $months;
        } else {
            $monthlyIntrest = ($request->insterest_rate / 100) * $startingBalance;
            $interestAccrued = $monthlyIntrest * $months;
            $finalBalance = $interestAccrued + $startingBalance;
        }

        $results = [
            'finalBalance' => $finalBalance,
            'initialBalance' => $startingBalance,
            'interestAccrued' => $interestAccrued,
            'monthlyIntrest' => $monthlyIntrest,
            'startDate' => $startDate,
            'months' => $months,
            'inputs' => $request->input()
        ];

        return view('tools.simple-interest-calculator', compact('results', 'tool'));
    }
}
