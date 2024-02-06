<?php

namespace App\Tools;

use App\Models\Tool;
use Illuminate\Http\Request;
use App\Traits\ToolsPostAction;
use App\Components\ToolsManager;
use App\Contracts\ToolInterface;
use App\Traits\LibreofficeFields;

class PdfWordConverter implements ToolInterface
{
    use LibreofficeFields, ToolsPostAction;

    public function render(Request $request, Tool $tool)
    {
        return view('tools.pdf-word-converter', compact('tool'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $validated = $request->validate([
            'files' => "required|max:{$tool->no_file_tool}",
            'files.*' => "required|mimes:pdf|max:" . convert_mb_into_kb($tool->fs_tool)
        ]);

        $request->request->add(['format' => 'docx']);
        $request->request->add(['filter' => 'MS Word 2007 XML']);
        $request->request->add(['options' => ['--infilter="writer_pdf_import"']]);

        $driver = (new ToolsManager($tool))->driver();
        $result = $driver->parse($request);

        if (!$result['success']) {
            return redirect()->back()->withErrors($result['message']);
        }

        if ($result['files'] && count($result['files']) == 0) {
            return redirect()->back()->withErrors(__('tools.countNotConvert'));
        }

        $results = [
            'files' => $result['files'],
            'process_id' => $result['process_id']
        ];

        return view('tools.pdf-word-converter', compact('results', 'tool'));
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
                    'options' => [['text' => "LibreOffice", 'value' => "LibreOffice"], ['text' => "PDF2DOCX", 'value' => "pDFToDocx"]],
                    'validation' => "required",
                    'type' => 'dropdown',
                    'classes' => "",
                    'dependant' => null,
                ],
                [
                    'id' => "libre_office_path",
                    'field' => "tool-options-textfield",
                    'placeholder' => "Please enter LibreOffice bin path here....",
                    'label' => "Binary Path (optional)",
                    'required' => false,
                    'options' => null,
                    'validation' => "nullable",
                    'type' => 'text',
                    'min' => null,
                    'max' => null,
                    'classes' => "",
                    'dependant' => ['settings[driver]', "LibreOffice"],
                ],
                [
                    'id' => "docx_bin_path",
                    'field' => "tool-options-textfield",
                    'placeholder' => "Please enter PDF2DOCX bin path here....",
                    'label' => "Binary Path (optional)",
                    'required' => false,
                    'options' => null,
                    'validation' => "nullable",
                    'type' => 'text',
                    'min' => null,
                    'max' => null,
                    'classes' => "",
                    'dependant' => ['settings[driver]', "pDFToDocx"],
                ],
            ],
            "default" => ['driver' => 'LibreOffice', 'libre_office_path' => '']
        ];

        return $array;
    }
}
