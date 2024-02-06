<?php

namespace App\Tools;

use App\Models\Tool;
use Illuminate\Http\Request;
use App\Traits\ToolsPostAction;
use App\Components\ToolsManager;
use App\Contracts\ToolInterface;
use App\Traits\GhostscriptFields;

class PdfCompressor implements ToolInterface
{
    use GhostscriptFields, ToolsPostAction;

    public function render(Request $request, Tool $tool)
    {
        return view('tools.pdf-compressor', compact('tool'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $request->request->add(['grayscale' => false]);
        $request->request->add(['device' => 'pdfwrite']);
        $request->request->add(['output' => 'pdf']);
        $request->request->add(['merge_pages' => '1']);
        $request->request->add(['filename' => $tool->slug]);
        $request->request->add(['arguments' => ['-dPDFSETTINGS=/screen']]);

        $driver = (new ToolsManager($tool))->driver();
        if (method_exists($driver, 'setTask')) {
            $driver->setTask('compress');
        }
        $results = $driver->parse($request);

        if (!$results) {
            return redirect()->back()->withErrors(__('common.somethingWentWrong'));
        }

        return view('tools.pdf-compressor', compact('tool', 'results'));
    }
}
