<?php

namespace App\Tools;

use App\Models\Tool;
use Illuminate\Http\Request;
use App\Components\ToolsManager;
use App\Contracts\ToolInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class JPGToWord implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        return view('tools.jpg-to-word', compact('tool'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $validated = $request->validate([
            'image' => "required|image|mimes:jpeg,jpg|max:" . convert_mb_into_kb($tool->fs_tool),
        ]);

        $file = $request->file('image');
        $driver = (new ToolsManager($tool))->driver();
        $results = $driver->parse($file);

        $results['download_url'] = route('tool.action', ['tool' => $tool->slug, 'action' => 'download', 'process_id' => $results['process_id']]);

        if (empty($results['text'])) {
            return redirect()->back()->withError(__('tools.noTextFoundOnImg'));
        }

        return view('tools.jpg-to-word', compact('tool', 'results'));
    }

    public function action(Request $request)
    {
        $validator = Validator::make(['process_id' => $request->process_id], [
            'process_id' => 'required|uuid',
        ]);

        if ($validator->fails()) {
            return $request->wantsJson() ? response()->json(['status' => false, 'message' => __('tools.invalidRequest')]) : redirect()->back()->withError(__('tools.invalidRequest'));
        }

        $process_id = $request->process_id;

        // Fetch Job
        $job = Cache::has($process_id);
        if (!$job) {
            return $request->wantsJson() ? response()->json(['status' => false, 'message' => __('tools.theRequestExpired')]) : redirect()->back()->withError(__('tools.theRequestExpired'));
        }

        list($content, $filename) = Cache::get($request->process_id);
        $headers = array(
            "Content-type" => "text/html",
            "Content-Disposition" => "attachment;Filename={$filename}.doc"
        );

        return response($content, 200, $headers);
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
                    'options' => [['text' => "Tesseract", 'value' => "TesseractOCR"], ['text' => "OCR Space", 'value' => "OcrSpace"], ['text' => 'Google Vision', 'value' => 'GoogleVisionOcr']],
                    'validation' => "required",
                    'type' => 'dropdown',
                    'classes' => "",
                    'dependant' => null,
                ],
                [
                    'id' => "ocr_space_key",
                    'field' => "tool-options-textfield",
                    'placeholder' => "please enter api key here....",
                    'label' => "OCRSpace Driver Api Key",
                    'required' => true,
                    'options' => null,
                    'validation' => "required_if:driver,OcrSpace",
                    'type' => 'text',
                    'min' => null,
                    'max' => null,
                    'classes' => "",
                    'dependant' => ['settings[driver]', "OcrSpace"],
                ],
            ],
            "default" => ['driver' => 'OcrSpace', 'ocr_space_key' => 'helloworld']
        ];

        return $array;
    }
}
