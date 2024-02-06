<?php

namespace App\Tools;

use App\Models\Tool;
use Illuminate\Http\Request;
use App\Contracts\ToolInterface;


class SalesTaxCalculator implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        $type = 1;

        return view('tools.sales-tax-calculator', compact('tool', 'type'));
    }

    public function handle(Request $request, Tool $tool)
    {
        abort(404);
    }
}
