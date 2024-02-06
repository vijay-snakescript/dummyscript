<?php

namespace App\Tools;

use App\Models\Faqs;
use App\Models\Plan;
use App\Models\Tool;
use App\Models\Property;
use Illuminate\Http\Request;
use App\Contracts\ToolInterface;
use Asika\Minifier\MinifierFactory;

class CssMinify implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        return view('tools.css-minify', compact('tool'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $validated = $request->validate([
            'css' => 'required'
        ]);

        $css = $request->input('css');
        $minify = MinifierFactory::create('css');
        $minify->addContent($css);
        $content = $minify->minify();
        $input_size = mb_strlen($css, '8bit');
        $out_size = mb_strlen($content, '8bit');
        $save_size = 100 - (($out_size / $input_size) * 100);

        $results = [
            'css' => $css,
            'content' => $content,
            'input_size' => $input_size,
            'output_size' => $out_size,
            'save_size' => round($save_size, 2)
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

            return view('tools.pages.css-minify', compact('results', 'tool', 'plans', 'faqs', 'properties', 'relevant_tools'));
        }

        return view('tools.css-minify', compact('results', 'tool'));
    }

    public function index(Tool $tool, $relevant_tools, $plans, $faqs, $properties)
    {
        $tool->index_content = str_replace("[x-tool-form]", $this->toolForm($tool), $tool->index_content);

        return view('tools.pages.css-minify', compact('tool', 'relevant_tools', 'plans', 'properties', 'faqs'));
    }

    public function toolForm($tool, $results = null)
    {
        return view('tools.pages.forms.css-minify', compact('tool', 'results'))->render();
    }

    public function indexContent()
    {
        $data = '<div class="raw-html-embed">
<div class="cssmin-max-wrap">
<div class="banner-area">
  <div class="container">
    <div class="row">
      <div class="col-md-6 align-self-center">
        <h1>CSS Minifier</h1>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In malesuada posuere metus
          a sollicitudin. Nam a felis tellus. Sed tempor at quam sed feugiat. Nunc sodales vel
          purus vel euismod.</p>
        <div class="mt-3">
          <button type="button" class="btn btn-primary rounded-pill mb-2">Go Pro</button>
          <button type="button" class="btn btn-outline-primary rounded-pill mb-2">Try it free</button>
        </div>
      </div>
      <div class="col-md-6 text-center">
        <img class="img-fluid" src="/themes/canvas/images/css-minifier.svg" alt="CSS Minifier">
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
        <img class="mb-3" src="/themes/canvas/images/css-text-use1.svg" alt="">
        <h2 class="title">Select</h2>
        <p>Upload your image for resize.</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="item text-center">
        <img class="mb-3" src="/themes/canvas/images/css-text-use2.svg" alt="">
        <h2 class="title">Resize</h2>
        <p>Resize image your own.</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="item text-center">
        <img class="mb-3" src="/themes/canvas/images/css-text-use3.svg" alt="">
        <h2 class="title">Download</h2>
        <p>Download your resized image.</p>
      </div>
    </div>
  </div>
</div>
<div class="easy-convert section-padding">
<div class="container">
  <div class="row">
    <div class="col-md-6">
      <img class="text-center" src="/themes/canvas/images/minifier-img.svg" alt="image">
    </div>
    <div class="col-md-6 align-self-center">
      <div class="hero-title bold">
        <h1>Easily convert your code</h1>
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
</div></div></div>';
        return $data;
    }
}
