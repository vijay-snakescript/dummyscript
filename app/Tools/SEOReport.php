<?php

namespace App\Tools;

use App\Models\Faqs;
use App\Models\Plan;
use App\Models\Tool;
use App\Models\Property;
use App\Helpers\Facads\SEO;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Contracts\ToolInterface;
use Illuminate\Support\Facades\Cache;

class SEOReport implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        return view('tools.seo-report', compact('tool'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $request->validate([
            'url' => 'required|url',
        ]);

        try {
            $result = SEO::analyze($request->url)->report()->get();
            $uuid = Str::uuid()->toString();
            Cache::put($uuid . "-seo-report", $result, job_cache_time());
            $results = [
                'result' => $result,
                'url' => $request->url,
                'uuid' => $uuid
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

                return view('tools.pages.seo-report', compact('results', 'tool', 'plans', 'faqs', 'properties', 'relevant_tools'));
            }
            return view('tools.seo-report', compact('results', 'tool'));
        } catch (\Exception $e) {
            return redirect()->back()->withError($e->getMessage());
        }
    }

    public function postAction(Request $request, Tool $tool)
    {
        $validator = $request->validate([
            'process_id' => 'required|uuid',
        ]);

        if (!Cache::has($request->process_id . "-seo-report")) {
            return redirect()->back();
        }

        $result = Cache::get($request->process_id . "-seo-report");
        $results = [
            'result' => $result,
            'hostname' => $result['url'],
            'uuid' => $request->process_id
        ];

        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
            ->setBasePath(url('/'))
            ->loadView('tools.pdf.seo-tool-pdf', compact('results', 'tool'))
            ->setPaper('a4');

        return $pdf->download($result['domainname'] . 'seo-report.pdf');
    }

    public static function getFileds()
    {
        $array =  [
            'title' => "Drivers",
            'fields' => [
                [
                    'id' => "driver",
                    'field' => "tool-options-select",
                    'placeholder' => "Driver",
                    'label' => "Driver",
                    'required' => true,
                    'options' => [['text' => "Thum", 'value' => "thum"], ['text' => "MicroLink", 'value' => "microlink"]],
                    'validation' => "required",
                    'type' => 'dropdown',
                    'classes' => "",
                    'dependant' => null,
                ],
                [
                    'id' => "thumio_auth_code",
                    'field' => "tool-options-textfield",
                    'placeholder' => "please enter api key here....",
                    'label' => "Thum Auth Code",
                    'required' => true,
                    'options' => null,
                    'validation' => "nullable",
                    'type' => 'text',
                    'min' => null,
                    'max' => null,
                    'classes' => "",
                    'dependant' => ['settings[driver]', "thum"],
                ],
            ],
            'default' => ['driver' => 'thum', 'thumio_auth_code' => null],
        ];

        return $array;
    }

    public function index(Tool $tool, $relrelevant_tools, $plans, $faqs, $propertiesevant_tools)
    {
        $tool->index_content = str_replace("[x-tool-form]", $this->toolForm($tool), $tool->index_content);

        return view('tools.pages.seo-report', compact('tool', 'plans', 'faqs', 'properties', 'relevant_tools'));
    }

    public function toolForm($tool, $results = null)
    {
        return view('tools.pages.forms.seo-report', compact('tool', 'results'))->render();
    }

    public function indexContent()
    {
        $data = '<div class="raw-html-embed"><div class="seo-analysis"><div class="banner-area">
  <div class="container">
    <div class="row">
      <div class="col-md-6 align-self-center">
        <h1>Advanced SEO Analysis platform</h1>
        <p>Rewrite any given text into readable text along. To use this Article Rewriter, please copy and paste
          your content into the text box below.</p>
        <div class="mt-3">
          <a href="/plans" class="btn btn-primary rounded-pill mb-2">Go Pro</a>
          <a href="#try-it-free" class="btn btn-outline-primary rounded-pill mb-2">Try it free</a>
        </div>
      </div>
      <div class="col-md-6">
        <img class="img-fluid" src="/themes/canvas/images/seo-main-img.svg" alt="Advanced SEO Analysis platform">
      </div>
    </div>
  </div>
</div>
[x-tool-form]
<div class="container pt-5">
  <div class="row">
    <div class="col-md-4 d-flex justify-content-center pt-5 pb-5">
      <div class="img-wrap">
        <img src="/themes/canvas/images/historical-keywords.svg" alt="img">
      </div>
      <div class="contant ps-3">
        <h3>+1.5 billions</h3>
        <p>Historical keywords</p>
      </div>
    </div>
    <div class="col-md-4 d-flex justify-content-center pt-5 pb-5">
      <div class="img-wrap">
        <img src="/themes/canvas/images/seo-analysis.svg" alt="img">
      </div>
      <div class="contant ps-3">
        <h3>+10 milions</h3>
        <p>Of SEO Analysis every year</p>
      </div>
    </div>
    <div class="col-md-4 d-flex justify-content-center pt-5 pb-5">
      <div class="img-wrap">
        <img src="/themes/canvas/images/glob.svg" alt="img">
      </div>
      <div class="contant ps-3">
        <h3>+500K users</h3>
        <p>All around the world</p>
      </div>
    </div>
  </div>
</div>
<div class="container pt-5">
  <div class="row seo-img-wrap">
    <div class="col-md-6">
      <div class="img-wrap">
        <img class="img-fluid" src="/themes/canvas/images/seo-audit.svg" alt="img">
      </div>
    </div>
    <div class="col-md-6 align-self-center">
      <div class="hero-title">
        <h2>Seo Audit</h2>
      </div>
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce eu rutrum tellus. Sed iaculis, tortor
        tempus blandit interdum,
        ipsum augue feugiat nisi, sit amet vehicula nulla lectus nec risus. Aenean convallis massa facilisis,
        dignissim ligula in, imp
        erdiet massa. Praesent laoreet risus nunc. Sed finibus ligula ac nisi finibus facilisis. Phasellus
        sollicitudin gravida arcu, ac
        luctus lectus finibus sed. Suspendisse fermentum tristique libero et vestibulum. Vivamus condimentum
        egestas sollicitudin.
        Curabitur ac nibh eget leo accumsan elementum at vel libero. Orci varius natoque penatibus et magnis dis
        parturient montes,
        nascetur ridiculus mus.</p>
    </div>
  </div>
</div>
<div class="img-wrap">
  <img class="img-fluid" src="/themes/canvas/images/lines-back.svg" alt="img">
</div>
<div class="container">
  <div class="row seo-img-wrap">
    <div class="col-md-6 align-self-center">
      <div class="hero-title">
        <h2>Page quality</h2>
      </div>
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce eu rutrum tellus. Sed iaculis, tortor
        tempus blandit interdum,
        ipsum augue feugiat nisi, sit amet vehicula nulla lectus nec risus. Aenean convallis massa facilisis,
        dignissim ligula in, imp
        erdiet massa. Praesent laoreet risus nunc. Sed finibus ligula ac nisi finibus facilisis. Phasellus
        sollicitudin gravida arcu, ac
        luctus lectus finibus sed. Suspendisse fermentum tristique libero et vestibulum. Vivamus condimentum
        egestas sollicitudin.
        Curabitur ac nibh eget leo accumsan elementum at vel libero. Orci varius natoque penatibus et magnis dis
        parturient montes,
        nascetur ridiculus mus.</p>
    </div>
    <div class="col-md-6">
      <div class="img-wrap">
        <img class="img-fluid" src="/themes/canvas/images/page-quality.svg" alt="img">
      </div>
    </div>
  </div>
</div>
<div class="img-wrap">
  <img class="img-fluid" src="/themes/canvas/images/lines back.svg" alt="img">
</div>
<div class="container">
  <div class="row seo-img-wrap">
    <div class="col-md-6">
      <div class="img-wrap">
        <img class="img-fluid" src="/themes/canvas/images/meta-information.svg" alt="img">
      </div>
    </div>
    <div class="col-md-6 align-self-center">
      <div class="hero-title">
        <h2>Meta information</h2>
      </div>
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce eu rutrum tellus. Sed iaculis, tortor
        tempus blandit interdum,
        ipsum augue feugiat nisi, sit amet vehicula nulla lectus nec risus. Aenean convallis massa facilisis,
        dignissim ligula in, imp
        erdiet massa. Praesent laoreet risus nunc. Sed finibus ligula ac nisi finibus facilisis. Phasellus
        sollicitudin gravida arcu, ac
        luctus lectus finibus sed. Suspendisse fermentum tristique libero et vestibulum. Vivamus condimentum
        egestas sollicitudin.
        Curabitur ac nibh eget leo accumsan elementum at vel libero. Orci varius natoque penatibus et magnis dis
        parturient montes,
        nascetur ridiculus mus.</p>
    </div>
  </div>
</div>
<div class="container">
  <div class="row seo-img-wrap mt-5">
    <div class="col-md-6 align-self-center">
      <div class="hero-title">
        <h2>Page and link structure</h2>
      </div>
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce eu rutrum tellus. Sed iaculis, tortor
        tempus blandit interdum,
        ipsum augue feugiat nisi, sit amet vehicula nulla lectus nec risus. Aenean convallis massa facilisis,
        dignissim ligula in, imp
        erdiet massa. Praesent laoreet risus nunc. Sed finibus ligula ac nisi finibus facilisis. Phasellus
        sollicitudin gravida arcu, ac
        luctus lectus finibus sed. Suspendisse fermentum tristique libero et vestibulum. Vivamus condimentum
        egestas sollicitudin.
        Curabitur ac nibh eget leo accumsan elementum at vel libero. Orci varius natoque penatibus et magnis dis
        parturient montes,
        nascetur ridiculus mus.</p>
    </div>
    <div class="col-md-6">
      <div class="img-wrap">
        <img class="img-fluid" src="/themes/canvas/images/link-structure.svg" alt="img">
      </div>
    </div>
  </div>
</div></div></div>';

        return $data;
    }
}
