<?php

namespace App\Tools;

use App\Models\Tool;
use Illuminate\Http\Request;
use ZanySoft\Zip\Facades\Zip;
use App\Components\ToolsManager;
use App\Contracts\ToolInterface;
use Illuminate\Support\Facades\Storage;

class HtmlToPdfConverter implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        return view('tools.html-pdf-converter', compact('tool'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $validated = $request->validate([
            'url' => "required|url",
        ]);

        $driver = (new ToolsManager($tool))->driver();
        $result = $driver->urlToPDF($request);

        if (!$result['success']) {
            return redirect()->back()->withErrors($result['message']);
        }

        $results = [
            'files' => $result['files'],
            'process_id' => $result['process_id']
        ];

        return view('tools.html-pdf-converter', compact('results', 'tool'));
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
                    'options' => [['text' => "ILovePDF", 'value' => "ILovePdf"]],
                    'validation' => "required",
                    'type' => 'dropdown',
                    'classes' => "",
                    'dependant' => null,
                ],
                [
                    'id' => "love_pdf_public_id",
                    'field' => "tool-options-textfield",
                    'placeholder' => "Please enter ILovePdf Public ID here....",
                    'label' => "Public ID",
                    'required' => true,
                    'options' => null,
                    'validation' => "required_if:driver,ILovePdf",
                    'type' => 'text',
                    'min' => null,
                    'max' => null,
                    'classes' => "",
                    'dependant' => ['settings[driver]', "ILovePdf"],
                ],
                [
                    'id' => "love_pdf_secret_key",
                    'field' => "tool-options-textfield",
                    'placeholder' => "Please enter ILovePdf secret key here....",
                    'label' => "Secret Key",
                    'required' => true,
                    'options' => null,
                    'validation' => "required_if:driver,ILovePdf",
                    'type' => 'text',
                    'min' => null,
                    'max' => null,
                    'classes' => "",
                    'dependant' => ['settings[driver]', "ILovePdf"],
                ],
            ],
            "default" => ['driver' => 'LibreOffice', 'libre_office_path' => '']
        ];

        return $array;
    }
}
