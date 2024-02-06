<?php

namespace App\Tools;

use App\Models\Faqs;
use App\Models\Plan;
use App\Models\Tool;
use App\Models\Property;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use ZanySoft\Zip\Facades\Zip;
use App\Components\ToolsManager;
use App\Contracts\ToolInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ImageCompressor implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        return view('tools.image-compressor', compact('tool'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $validated = $request->validate([
            'images' => "required|max:{$tool->no_file_tool}",
            'images.*' => "image|mimes:png,jpeg,jpg|max:" . convert_mb_into_kb($tool->fs_tool)
        ]);
        $images = $request->file('images');
        $process_id = (string) Str::uuid();

        $files = Cache::remember($process_id, job_cache_time(), function () use ($images) {
            $uploadedFiles = collect();
            foreach ($images as $image) {
                $file = tempFileUpload($image);
                if ($file) {
                    $uploadedFiles->push($file);
                }
            }

            return $uploadedFiles;
        });

        $results = [
            'files' => $files,
            'process_id' => $process_id
        ];

        if ($tool->is_home == 1 && empty($request->route()->parameters)) {
            $relevant_tools = Tool::with('translations')->withCount('usageToday')->with('category')->active()->take('16')->get();
            $tool->index_content = str_replace("[x-tool-form]", $this->toolForm($tool, $results), $tool->index_content);

            $plans = Plan::active()
                ->with('properties')
                ->with('translations')
                ->get();

            $faqs = Faqs::active()->get();
            $properties = Property::active()->with('translations')->get();

            return view('tools.pages.image-compressor', compact('results', 'tool', 'plans', 'faqs', 'properties', 'relevant_tools'));
        }

        return view('tools.image-compressor', compact('results', 'tool'));
    }

    public function postAction(Request $request, $tool)
    {
        $action = $request->action;

        switch ($action) {
            case 'process-file':
                $image = $this->processSingleFile($request, $tool);
                return $image;
                break;
            case 'download-all':
                return $this->downloadAllImages($request, $tool);
                break;
        }

        abort(404);
    }

    protected function downloadAllImages($request, $tool)
    {
        $process_id = $request->process_id;
        // Get last cached resource
        $result = Cache::get($process_id . "-download-all");

        // Make path for all images
        $path = pathinfo($result['file_path'])['dirname'];
        $job = Storage::disk($result['disk'])->path($path);

        // Zip store location & path.
        $storeDisk = Storage::disk(config('artisan.temporary_files_disk', 'local'));
        $storePath = config('artisan.temporary_files_path', 'temp') . DIRECTORY_SEPARATOR . $process_id;
        $storeDisk->makeDirectory($storePath);
        $zip = Zip::create($storeDisk->path("{$storePath}/{$tool->slug}.zip"));
        $zip->add($job, true);
        $zip->close();

        return $storeDisk->download("{$storePath}/{$tool->slug}.zip");
    }

    protected function processSingleFile($request, $tool)
    {
        $validator = Validator::make($request->all(), [
            'process_id' => 'required|uuid',
            'file' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => __('tools.invalidRequest')]);
        }

        if ($tool->settings && $tool->settings->driver == 'ImageCompressor' && !empty($tool->settings->binary_path)) {
            Config::set('image-optimizer.binary_path', $tool->settings->binary_path);
        }

        $driver = (new ToolsManager($tool))->driver();
        return $driver->parse($request);
    }

    public function index(Tool $tool, $relevant_tools, $plans, $faqs, $properties)
    {
        $tool->index_content = str_replace("[x-tool-form]", $this->toolForm($tool), $tool->index_content);

        return view('tools.pages.image-compressor', compact('tool', 'relevant_tools', 'plans', 'properties', 'faqs'));
    }

    public function toolForm($tool, $results = null)
    {
        return view('tools.pages.forms.image-compressor', compact('tool', 'results'))->render();
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
                    'options' => [['text' => "Tiny PNG", 'value' => "TinypngApiCompressor"], ['text' => "Image Compressor", 'value' => "ImageCompressor"]],
                    'validation' => "required",
                    'type' => 'dropdown',
                    'classes' => "",
                    'dependant' => null,
                ],
                [
                    'id' => "tinypng_apikey",
                    'field' => "tool-options-textfield",
                    'placeholder' => "please enter api key here....",
                    'label' => "Tiny PNG Driver Api Key",
                    'required' => true,
                    'options' => null,
                    'validation' => "required_if:driver,TinypngApiCompressor",
                    'type' => 'text',
                    'min' => null,
                    'max' => null,
                    'classes' => "",
                    'dependant' => ['settings[driver]', "TinypngApiCompressor"],
                ],
                [
                    'id' => "binary_path",
                    'field' => "tool-options-textfield",
                    'placeholder' => "some/path/here",
                    'label' => "Binary Path",
                    'required' => false,
                    'options' => null,
                    'validation' => "nullable",
                    'type' => 'text',
                    'min' => null,
                    'max' => null,
                    'classes' => "",
                    'dependant' => ['settings[driver]', "ImageCompressor"],
                ],
            ],
            "default" => ['driver' => 'TinypngApiCompressor', 'tinypng_apikey' => '']
        ];

        return $array;
    }

    public function indexContent()
    {
        $data = '<div class="raw-html-embed"><div class="image-converter">
  <div class="banner-area">
    <div class="container">
      <div class="row">
        <div class="col-md-6 align-self-center">
          <h1>Image Converter</h1>
          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In malesuada posuere metus
            a sollicitudin. Nam a felis tellus. Sed tempor at quam sed feugiat. Nunc sodales vel
            purus vel euismod.</p>
          <div class="mt-3">
            <a href="/plans" class="btn btn-primary rounded-pill mb-2">Go Pro</a>
            <a href="#try-it-free" class="btn btn-outline-primary rounded-pill mb-2">Try it free</a>
          </div>
        </div>
        <div class="col-md-6">
          <img class="img-fluid" src="/themes/canvas/images/main-image-converter.svg" alt="Image Converter">
        </div>
      </div>
    </div>
  </div>
  [x-tool-form]
  <div class="container pt-5">
    <div class="row image-converter-wrap">
      <div class="col-md-4">
        <div class="item">
          <i class="an an-reload"></i>
          <h2 class="title">Fast conversion</h2>
          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce eu rutrum tellus. Sed iaculis, tortor
            tempus blandit interdum,
            ipsum augue feugiat nisi,
          </p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="item">
          <i class="an an-browser"></i>
          <h2 class="title">Works online</h2>
          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce eu rutrum tellus. Sed iaculis, tortor
            tempus blandit interdum,
            ipsum augue feugiat nisi,
          </p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="item">
          <i class="an an-image"></i>
          <h2 class="title">Support any format</h2>
          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce eu rutrum tellus. Sed iaculis, tortor
            tempus blandit interdum,
            ipsum augue feugiat nisi,
          </p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="item">
          <i class="an an-thumbs-up"></i>
          <h2 class="title">User-friendly</h2>
          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce eu rutrum tellus. Sed iaculis, tortor
            tempus blandit interdum,
            ipsum augue feugiat nisi,
          </p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="item">
          <i class="an an-cog"></i>
          <h2 class="title">How to convert</h2>
          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce eu rutrum tellus. Sed iaculis, tortor
            tempus blandit interdum,
            ipsum augue feugiat nisi,
          </p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="item">
          <i class="an an-shield-sword"></i>
          <h2 class="title">Secure converter</h2>
          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce eu rutrum tellus. Sed iaculis, tortor
            tempus blandit interdum,
            ipsum augue feugiat nisi,
          </p>
        </div>
      </div>
    </div>
  </div>
  <div class="container pt-5">
    <div class="hero-title center">
      <h2>Supported Formats</h2>
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque in leo quis neque aliquet interdum.
        Suspendisse potenti. Nunc aliquet porttitor auctor..</p>
    </div>
    <table class="table table-style">
      <thead>
        <tr>
          <th scope="col">Format</th>
          <th scope="col">Description</th>
          <th scope="col">Conversions</th>
          <th scope="col"></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><span class="ps-2">JPG</span></td>
          <td>Joint Photographic Group</td>
          <td>JPG Converter</td>
          <td>
            <button class="btn btn-outline-primary rounded-circle" type="button" id="button" data-toggle="tooltip" aria-label="Convert Now">
              <i class="an an-long-arrow-down an-rotate-270"></i>
            </button>
          </td>
        </tr>
        <tr>
          <td><span class="ps-2">JPEG</span></td>
          <td>Joint Photographic Experts Group</td>
          <td>JPEG Converter</td>
          <td>
            <button class="btn btn-outline-primary rounded-circle" type="button" id="button" data-toggle="tooltip" aria-label="Convert Now">
              <i class="an an-long-arrow-down an-rotate-270"></i>
            </button>
          </td>
        </tr>
        <tr>
          <td><span class="ps-2">PNG</span></td>
          <td>Portable Network Graphic</td>
          <td>PNG Converter</td>
          <td>
            <button class="btn btn-outline-primary rounded-circle" type="button" id="button" data-toggle="tooltip" aria-label="Convert Now">
              <i class="an an-long-arrow-down an-rotate-270"></i>
            </button>
          </td>
        </tr>
        <tr>
          <td><span class="ps-2">GIF</span></td>
          <td>Graphics Interchange Format</td>
          <td>GIF Maker</td>
          <td>
            <button class="btn btn-outline-primary rounded-circle" type="button" id="button" data-toggle="tooltip" aria-label="Convert Now">
              <i class="an an-long-arrow-down an-rotate-270"></i>
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div></div>';
        return $data;
    }
}
