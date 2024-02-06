<?php

namespace App\Tools;

use App\Models\Tool;
use Illuminate\Http\Request;
use App\Traits\ToolsPostAction;
use App\Components\ToolsManager;
use App\Contracts\ToolInterface;
use App\Traits\GhostscriptFields;

class PdfGrayscale implements ToolInterface
{
    use GhostscriptFields, ToolsPostAction;

    public function render(Request $request, Tool $tool)
    {
        return view('tools.pdf-grayscale', compact('tool'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $request->request->add(['grayscale' => true]);
        $request->request->add(['device' => 'pdfwrite']);
        $request->request->add(['output' => 'pdf']);
        $request->request->add(['filename' => $tool->slug]);
        $request->request->add(['arguments' => ['-r300']]);

        $driver = (new ToolsManager($tool))->driver();
        $results = $driver->parse($request);

        if (!$results) {
            return redirect()->back()->withErrors(__('common.somethingWentWrong'));
        }

        return view('tools.pdf-grayscale', compact('tool', 'results'));
    }

    public static function getFileds()
    {
        $array = [
            'title' => "Drivers",
            'fields' => [
                [
                    'id' => "driver",
                    'field' => "tool-options-select",
                    'placeholder' => "Driver",
                    'label' => "Driver",
                    'required' => true,
                    'options' => [['text' => "Ghostscript", 'value' => "ghostscript"]],
                    'validation' => "required",
                    'type' => 'dropdown',
                    'classes' => "",
                    'dependant' => null,
                ],
                [
                    'id' => "gs_bin_path",
                    'field' => "tool-options-textfield",
                    'placeholder' => "Please enter Ghostscript binary path",
                    'label' => "Binary Path (optional)",
                    'required' => false,
                    'options' => null,
                    'validation' => "nullable",
                    'type' => 'text',
                    'min' => null,
                    'max' => null,
                    'classes' => "",
                    'dependant' => ['settings[driver]', "ghostscript"],
                ],
            ],
            "default" => ['driver' => 'ghostscript']
        ];

        return $array;
    }
}
