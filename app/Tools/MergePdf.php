<?php

namespace App\Tools;

use App\Models\Tool;
use App\Traits\QPDFFields;
use Illuminate\Http\Request;
use App\Components\ToolsManager;
use App\Contracts\ToolInterface;

class MergePdf implements ToolInterface
{
    use QPDFFields;

    public function render(Request $request, Tool $tool)
    {
        return view('tools.merge-pdf', compact('tool'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $driver = (new ToolsManager($tool))->driver();
        if (method_exists($driver, 'setTask')) {
            $driver->setTask('merge');
        }
        $results = $driver->parse($request);

        if (!$results) {
            return redirect()->back()->withErrors(__('common.somethingWentWrong'));
        }

        return view('tools.merge-pdf', compact('tool', 'results'));
    }
}
