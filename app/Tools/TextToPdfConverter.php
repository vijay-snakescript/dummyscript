<?php

namespace App\Tools;

use App\Models\Tool;
use Illuminate\Http\Request;
use App\Traits\ToolsPostAction;
use App\Components\ToolsManager;
use App\Contracts\ToolInterface;
use App\Traits\LibreofficeFields;

class TextToPdfConverter implements ToolInterface
{
    use ToolsPostAction, LibreofficeFields;

    public function render(Request $request, Tool $tool)
    {
        return view('tools.text-pdf-converter', compact('tool'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $validated = $request->validate([
            'files' => "required|max:{$tool->no_file_tool}",
            'files.*' => "required|mimes:txt|max:" . convert_mb_into_kb($tool->fs_tool)
        ]);

        $pages = json_decode($request->input('fileData'), true);
        $files = $request->file('files');

        $driver = (new ToolsManager($tool))->driver();
        $result = $driver->parse($request);

        if (!$result['success']) {
            return redirect()->back()->withErrors($result['message']);
        }

        $results = [
            'files' => $result['files'],
            'process_id' => $result['process_id']
        ];

        return view('tools.text-pdf-converter', compact('results', 'tool'));
    }
}
