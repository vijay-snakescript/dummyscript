<?php

namespace App\Tools;

use App\Models\Faqs;
use App\Models\Plan;
use App\Models\Tool;
use App\Models\Property;
use Illuminate\Http\Request;
use App\Contracts\ToolInterface;
use App\Helpers\Classes\WordpressThemeEngine;

class WordPressThemeDetector implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        return view('tools.word-press-theme-detector', compact('tool'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $validated = $request->validate([
            'url' => 'required|url',
        ]);

        $themeEngine = new WordpressThemeEngine($request->url);
        $themeEngine->fetch();
        $theme = $themeEngine->theme;
        $plugins = $themeEngine->plugins->toArray();

        if ($error = $themeEngine->lastError()) {
            return redirect()->back()->withError($error);
        }

        if (!$theme && !$plugins) {
            return redirect()->back()->withError(__('tools.noWPThemeDetected', ['url' => $themeEngine->url]));
        }

        $results = $themeEngine->results();

        if ($tool->is_home == 1 && empty($request->route()->parameters)) {
            $relevant_tools = Tool::with('translations')->withCount('usageToday')->with('category')->active()->take('16')->get();
            $tool->index_content = str_replace("[x-tool-form]", $this->toolForm($tool, $results, $theme, $plugins), $tool->index_content);

            $plans = Plan::active()
                ->with('properties')
                ->with('translations')
                ->get();

            $faqs = Faqs::active()->get();
            $properties = Property::active()->with('translations')->get();

            return view('tools.pages.wordpress-theme-detector', compact('results', 'tool', 'plans', 'faqs', 'properties', 'relevant_tools'));
        }
        return view('tools.word-press-theme-detector', compact('results', 'tool', 'theme', 'plugins'));
    }

    public function index(Tool $tool, $relevant_tools, $plans, $faqs, $properties)
    {
        $tool->index_content = str_replace("[x-tool-form]", $this->toolForm($tool), $tool->index_content);

        return view('tools.pages.wordpress-theme-detector', compact('tool', 'plans', 'faqs', 'properties', 'relevant_tools'));
    }

    public function toolForm($tool, $results = null, $theme = null, $plugins = null)
    {
        return view('tools.pages.forms.wordpress-theme-detector', compact('tool', 'results', 'theme', 'plugins'))->render();
    }

    public function indexContent()
    {
        $data = '<div class="raw-html-embed"><div class="wordpress-detector">
  <div class="banner-area">
    <div class="container">
      <div class="row">
        <div class="col-md-6 align-self-center">
          <h1>WrdPress Theme Detector</h1>
          <p>Want to check which WordPress theme a website is using? Use our free WordPress theme detector tool
            to find out what WordPress theme your competitor\'s website is using.</p>
          <div class="mt-3">
            <a href="/plans" class="btn btn-primary rounded-pill mb-2">Go Pro</a>
            <a href="#try-it-free" class="btn btn-outline-primary rounded-pill mb-2">Try it free</a>
          </div>
        </div>
        <div class="col-md-6">
          <img class="img-fluid" src="/themes/canvas/images/wp-main-img.svg" alt="WrdPress Theme Detector">
        </div>
      </div>
    </div>
  </div>
  [x-tool-form]
  <div class="container">
    <div class="row theme-detector-wrap mt-5">
      <div class="col-md-6">
        <div class="img-wrap">
          <img class="img-fluid" src="/themes/canvas/images/wp-themes.svg" alt="img">
        </div>
      </div>
      <div class="col-md-6 align-self-center">
        <div class="hero-title">
          <h2>WordPress Theme Detector</h2>
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
    <div class="row theme-detector-wrap">
      <div class="col-md-6 align-self-center">
        <div class="hero-title">
          <h2>Wordpress plugins Detector</h2>
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
          <img class="img-fluid" src="/themes/canvas/images/wp-plugins.svg" alt="img">
        </div>
      </div>
    </div>
  </div>
</div></div>';
        return $data;
    }
}
