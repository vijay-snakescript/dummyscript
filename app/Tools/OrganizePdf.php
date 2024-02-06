<?php

namespace App\Tools;

use App\Models\Tool;
use Illuminate\Http\Request;
use App\Components\ToolsManager;
use App\Contracts\ToolInterface;

class OrganizePdf implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        return view('tools.organize-pdf', compact('tool'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $request->request->add(['merge_pages' => 1]);

        $driver = (new ToolsManager($tool))->driver();
        if (method_exists($driver, 'setTask')) {
            $driver->setTask('organize');
        }
        $results = $driver->parse($request);

        if (!$results) {
            return redirect()->back()->withErrors(__('common.somethingWentWrong'));
        }

        return view('tools.organize-pdf', compact('tool', 'results'));
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
                    'options' => [['text' => "QPDF", 'value' => "qpdfPages"]],
                    'validation' => "required",
                    'type' => 'dropdown',
                    'classes' => "",
                    'dependant' => null,
                ],
                [
                    'id' => "bin_path",
                    'field' => "tool-options-textfield",
                    'placeholder' => "Please enter QPDF binary path",
                    'label' => "Binary Path (optional)",
                    'required' => false,
                    'options' => null,
                    'validation' => "nullable",
                    'type' => 'text',
                    'min' => null,
                    'max' => null,
                    'classes' => "",
                    'dependant' => ['settings[driver]', "qpdfPages"],
                ],
            ],
            "default" => ['driver' => 'qpdfPages']
        ];

        return $array;
    }
}
