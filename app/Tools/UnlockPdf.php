<?php

namespace App\Tools;

use App\Models\Tool;
use App\Traits\QPDFFields;
use Illuminate\Http\Request;
use App\Components\ToolsManager;
use App\Contracts\ToolInterface;

class UnlockPdf implements ToolInterface
{
    use QPDFFields;

    public function render(Request $request, Tool $tool)
    {
        return view('tools.unlock-pdf', compact('tool'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $request->validate([
            'files' => "required|array|max:1",
            'files.*' => "required|mimes:pdf|max:" . convert_mb_into_kb($tool->fs_tool)
        ]);

        $driver = (new ToolsManager($tool))->driver();
        if (method_exists($driver, 'setTask')) {
            $driver->setTask('unlock');
        }
        $results = $driver->parse($request);

        if (!$results['success']) {
            return redirect()->back()->withErrors(__('common.somethingWentWrong'));
        }

        return view('tools.unlock-pdf', compact('tool', 'results'));
    }
}
