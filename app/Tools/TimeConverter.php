<?php

namespace App\Tools;

use App\Models\Tool;
use Illuminate\Http\Request;
use App\Contracts\ToolInterface;

class TimeConverter implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        return view('tools.time-converter', compact('tool'));
    }

    public function handle(Request $request, Tool $tool)
    {
        abort(404);
    }
}
