<?php

namespace App\Tools;

use App\Models\Tool;
use Illuminate\Http\Request;
use App\Traits\ToolsPostAction;
use App\Components\ToolsManager;
use App\Contracts\ToolInterface;
use App\Traits\GhostscriptFields;

class PdfToJpgConverter implements ToolInterface
{
    use GhostscriptFields, ToolsPostAction;

    public function render(Request $request, Tool $tool)
    {
        return view('tools.pdf-jpg-converter', compact('tool'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $request->request->add(['is_image_tool' => 'jpg']);
        $request->request->add(['device' => 'jpeg']);
        $request->request->add(['output' => 'jpg']);
        $request->request->add(['filename' => 'pdf-to-jpg']);
        $request->request->add(['arguments' => ['-r300']]);

        $driver = (new ToolsManager($tool))->driver();
        if (method_exists($driver, 'setTask')) {
            $driver->setTask('pdfjpg');
        }
        $results = $driver->parse($request);

        if (!$results) {
            return redirect()->back()->withErrors(__('common.somethingWentWrong'));
        }

        return view('tools.pdf-jpg-converter', compact('tool', 'results'));
    }
}
