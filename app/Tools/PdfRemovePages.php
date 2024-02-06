<?php

namespace App\Tools;

use App\Models\Tool;
use App\Traits\QPDFFields;
use Illuminate\Http\Request;
use App\Components\ToolsManager;
use App\Contracts\ToolInterface;

class PdfRemovePages implements ToolInterface
{
    use QPDFFields;

    public function render(Request $request, Tool $tool)
    {
        return view('tools.pdf-remove-pages', compact('tool'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $driver = (new ToolsManager($tool))->driver();
        if (method_exists($driver, 'setTask')) {
            $driver->setTask('split');
        }
        $results = $driver->parse($request);

        if (!$results) {
            return redirect()->back()->withErrors(__('common.somethingWentWrong'));
        }

        return view('tools.pdf-remove-pages', compact('tool', 'results'));
    }
}
