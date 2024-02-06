<?php

namespace App\Tools;

use App\Models\Tool;
use App\Traits\QPDFFields;
use Illuminate\Http\Request;
use App\Traits\ToolsPostAction;
use App\Components\ToolsManager;
use App\Contracts\ToolInterface;

class LockPdf implements ToolInterface
{
    use QPDFFields, ToolsPostAction;

    public function render(Request $request, Tool $tool)
    {
        return view('tools.lock-pdf', compact('tool'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $request->validate([
            'files' => 'required|array',
            'files.*' => 'mimes:pdf',
            'password' => 'required|min:3',
        ]);

        $driver = (new ToolsManager($tool))->driver();
        if (method_exists($driver, 'setTask')) {
            $driver->setTask('protect');
        }
        $results = $driver->parseIndividually($request);

        if (!$results['files']) {
            return redirect()->back()->withErrors(__('common.somethingWentWrong'));
        }

        return view('tools.lock-pdf', compact('tool', 'results'));
    }
}
