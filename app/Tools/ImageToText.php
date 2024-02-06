<?php

namespace App\Tools;

use App\Models\Faqs;
use App\Models\Plan;
use App\Models\Tool;
use App\Models\Property;
use Illuminate\Http\Request;
use App\Components\ToolsManager;
use App\Contracts\ToolInterface;
use Illuminate\Support\Facades\Cache;

class ImageToText implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        return view('tools.image-to-text', compact('tool'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $validated = $request->validate([
            'image' => "required|image|max:" . $tool->fs_tool * 1024,
        ], [], [
            'image' => 'file'
        ]);
        $file = $request->file('image');

        $driver = (new ToolsManager($tool))->driver();
        $results = $driver->parse($file);

        $results['download_url'] = route('tool.action', ['tool' => $tool->slug, 'action' => 'download', 'process_id' => $results['process_id']]);

        if (empty($results['text'])) {
            return redirect()->back()->withError(__('tools.noTextFoundOnImg'));
        }

        if ($tool->is_home == 1 && empty($request->route()->parameters)) {
            $relevant_tools = Tool::with('translations')->withCount('usageToday')->with('category')->active()->take('16')->get();
            $tool->index_content = str_replace("[x-tool-form]", $this->toolForm($tool, $results), $tool->index_content);

            $plans = Plan::active()
                ->with('properties')
                ->with('translations')
                ->get();

            $faqs = Faqs::active()->get();
            $properties = Property::active()->with('translations')->get();

            return view('tools.pages.image-to-text', compact('results', 'tool', 'plans', 'faqs', 'properties', 'relevant_tools'));
        }

        return view('tools.image-to-text', compact('tool', 'results'));
    }

    public function action(Request $request)
    {
        list($content, $filename) = Cache::get($request->process_id);
        $headers = array(
            "Content-type" => "text/plain",
            "Content-Disposition" => "attachment;Filename={$filename}.txt"
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

    public function index(Tool $tool, $relevant_tools, $plans, $faqs, $properties)
    {
        $tool->index_content = str_replace("[x-tool-form]", $this->toolForm($tool), $tool->index_content);

        return view('tools.pages.image-to-text', compact('tool', 'relevant_tools', 'plans', 'properties', 'faqs'));
    }

    public function toolForm($tool, $results = null)
    {
        return view('tools.pages.forms.image-to-text', compact('tool', 'results'))->render();
    }

    public function indexContent()
    {
        $data = '<div class="raw-html-embed"><div class="content-writing-page"><div class="banner-area">
        <div class="container">
          <div class="row">
            <div class="col-md-6 align-self-center">
              <h1>Image to Text Converter.</h1>
              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In malesuada posuere metus
                a sollicitudin. Nam a felis tellus. Sed tempor at quam sed feugiat. Nunc sodales vel
                purus vel euismod.</p>
              <div class="mt-3">
                <button type="button" class="btn btn-primary rounded-pill mb-2">Go Pro</button>
                <button type="button" class="btn btn-outline-primary rounded-pill mb-2">Try it free</button>
              </div>
            </div>
            <div class="col-md-6 text-center">
              <img class="img-fluid" src="themes/canvas/images/img-to-text.svg" alt="Image Converter">
            </div>
          </div>
        </div>
      </div>
      [x-tool-form]
      <div class="container text-to-image-wrap section-padding">
        <div class="row">
          <div class="col-md-12">
            <div class="hero-title center bold">
              <h1>How to use the text to image converter.</h1>
              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque in leo quis neque aliquet
                interdum.
                Suspendisse potenti. Nunc aliquet porttitor auctor..</p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="item text-center">
              <img class="mb-3" src="themes/canvas/images/image-text-use1.svg" alt="">
              <h2 class="title">Scan Document</h2>
              <p>Lorem ipsum dolor sit amet.</p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="item text-center">
              <img class="mb-3" src="themes/canvas/images/image-text-use2.svg" alt="">
              <h2 class="title">Upload Image</h2>
              <p>Lorem ipsum dolor sit amet.</p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="item text-center">
              <img class="mb-3" src="themes/canvas/images/image-text-use3.svg" alt="">
              <h2 class="title">Convert to text</h2>
              <p>Lorem ipsum dolor sit amet.</p>
            </div>
          </div>
        </div>
      </div>
      <div class="easy-convert section-padding">
        <div class="container">
          <div class="row">
            <div class="col-md-6">
              <img class="w-100 px-5" src="themes/canvas/images/img-to-text-file.svg" alt="image">
            </div>
            <div class="col-md-6 align-self-center">
              <div class="hero-title bold">
                <h1>Easy convert images in to text</h1>
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been
                  the
                  industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type
                  and
                  scrambled it to make a type specimen book. It has survived not only five centuries, but also the
                  leap
                  into electronic typesetting, remaining essentially unchanged. <br><br> It was popularised in the
                  1960s
                  with the
                  release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop
                  publishing
                  software like Aldus PageMaker including versions of Lorem Ipsum.</p>
                <button type="button" class="btn btn-primary rounded-pill mb-2 px-5">Try it free</button>
              </div>
            </div>
          </div>
        </div>
      </div></div></div>';
        return $data;
    }
}
