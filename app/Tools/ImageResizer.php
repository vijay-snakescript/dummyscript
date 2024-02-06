<?php

namespace App\Tools;

use App\Models\Faqs;
use App\Models\Plan;
use App\Models\Tool;
use App\Models\Property;
use Illuminate\Http\Request;
use App\Contracts\ToolInterface;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;

class ImageResizer implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        return view('tools.image-resizer', compact('tool'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $validated = $request->validate([
            'image' => "image|max:" . $tool->fs_tool * 1024
        ]);

        $horizontal = $request->input('flip_horizontal');
        $vertical = $request->input('flip_vertical');
        $rotate = $request->input('rotate');
        $resize = $request->input('resize');
        $width = $request->input('width');
        $height = $request->input('height');
        $percentage = $request->input('percentage');
        $format = $request->input('format');
        $file = $request->file('image');
        $filename = $file->getClientOriginalName();
        $image = Image::make($file)->rotate($rotate, '#ffffff');

        if (in_array($format, ['png', 'jpg', 'webp'])) {
            $filename = pathinfo($filename, PATHINFO_FILENAME) . '.' . $format;
        } else {
            $format = $file->getClientOriginalExtension();
        }

        if ($resize == 1) {
            $height = round(($percentage * $image->height() / 100), 0);
            $width = round(($percentage * $image->width() / 100), 0);
        }

        if ($horizontal == 'true') {
            $image->flip('h');
        }
        if ($vertical == 'true') {
            $image->flip('h');
        }

        $image->fit($width, $height, function ($constraint) {
            $constraint->upsize();
        });

        $image->encode($format);
        $resource = UploadedFile::fake()->createWithContent($filename, $image);
        $results = tempFileUpload($resource, true);

        if ($tool->is_home == 1 && empty($request->route()->parameters)) {
            $relevant_tools = Tool::with('translations')->withCount('usageToday')->with('category')->active()->take('16')->get();
            $tool->index_content = str_replace("[x-tool-form]", $this->toolForm($tool, $results), $tool->index_content);

            $plans = Plan::active()
                ->with('properties')
                ->with('translations')
                ->get();

            $faqs = Faqs::active()->get();
            $properties = Property::active()->with('translations')->get();

            return view('tools.pages.image-resizer', compact('results', 'tool', 'plans', 'faqs', 'properties', 'relevant_tools'));
        }

        return view('tools.image-resizer', compact('results', 'tool'));
    }

    public function index(Tool $tool,$relevant_tools, $plans, $faqs, $properties)
    {
        $tool->index_content = str_replace("[x-tool-form]", $this->toolForm($tool), $tool->index_content);

        return view('tools.pages.image-resizer', compact('tool', 'relevant_tools', 'plans', 'properties', 'faqs'));
    }

    public function toolForm($tool, $results = null)
    {
        return view('tools.pages.forms.image-resizer', compact('tool', 'results'))->render();
    }

    public function indexContent()
    {
        $data = '<div class="raw-html-embed"><div class="resize-image-page"><div class="banner-area">
  <div class="container">
    <div class="row">
      <div class="col-md-6 align-self-center">
        <h1>Resize online your image easy and fast.</h1>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In malesuada posuere metus
          a sollicitudin. Nam a felis tellus. Sed tempor at quam sed feugiat. Nunc sodales vel
          purus vel euismod.</p>
        <div class="mt-3">
          <button type="button" class="btn btn-primary rounded-pill mb-2">Go Pro</button>
          <button type="button" class="btn btn-outline-primary rounded-pill mb-2">Try it free</button>
        </div>
      </div>
      <div class="col-md-6 text-center">
        <img class="img-fluid" src="themes/canvas/images/resize-image.svg" alt="Image Converter">
      </div>
    </div>
  </div>
</div>
[x-tool-form]
<div class="container image-resize-wrap section-padding">
  <div class="row">
    <div class="col-md-12">
      <div class="hero-title center bold">
        <h1>How to use the picture resizer.</h1>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque in leo quis neque aliquet
          interdum.
          Suspendisse potenti. Nunc aliquet porttitor auctor..</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="item text-center">
        <img class="mb-3" src="themes/canvas/images/how-to-use1.svg" alt="">
        <h2 class="title">Select</h2>
        <p>Upload your image for resize.</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="item text-center">
        <img class="mb-3" src="themes/canvas/images/how-to-use2.svg" alt="">
        <h2 class="title">Resize</h2>
        <p>Resize image your own.</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="item text-center">
        <img class="mb-3" src="themes/canvas/images/how-to-use3.svg" alt="">
        <h2 class="title">Download</h2>
        <p>Download your resized image.</p>
      </div>
    </div>
  </div>
</div>
<div class="easy-resizing section-padding">
  <div class="container">
    <div class="row">
      <div class="col-md-6">
        <img class="w-100" src="themes/canvas/images/easy-resizing-img.svg" alt="image">
      </div>
      <div class="col-md-6 align-self-center">
        <div class="hero-title bold">
          <h1>Easy Image Resizing for Any Purpose</h1>
          <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the
            industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and
            scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap
            into electronic typesetting, remaining essentially unchanged. <br><br> It was popularised in the 1960s
            with the
            release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing
            software like Aldus PageMaker including versions of Lorem Ipsum.</p>
          <button type="button" class="btn btn-primary rounded-pill mb-2 px-5">Try it free</button>
        </div>
      </div>
    </div>
  </div>
</div></div></div> ';
        return $data;
    }
}
