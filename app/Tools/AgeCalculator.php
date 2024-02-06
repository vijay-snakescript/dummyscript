<?php

namespace App\Tools;

use Carbon\Carbon;
use App\Models\Faqs;
use App\Models\Plan;
use App\Models\Tool;
use App\Models\Property;
use Illuminate\Http\Request;
use App\Contracts\ToolInterface;

class AgeCalculator implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        return view('tools.age-calculator', compact('tool'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $request->validate([
            'birth_year' => 'required',
            'birth_month' => 'required',
            'birth_day' => 'required',
            'from_year' => 'required',
            'from_month' => 'required',
            'from_day' => 'required',
        ]);

        $year = $request->input('birth_year');
        $month = $request->input('birth_month');
        $day = $request->input('birth_day');
        $year2 = $request->input('from_year');
        $month2 = $request->input('from_month');
        $day2 = $request->input('from_day');

        $from = Carbon::parse("{$year}-{$month}-{$day}");
        $to = Carbon::parse("{$year2}-{$month2}-{$day2}");

        $dob = $from->diff($to);
        $results['dob'] = $dob;
        $results['from'] = $from;
        $results['to'] = $to;
        $results['years'] = trans('tools.yourAgeY', ['y' => $from->diffInYears($to)]);
        $results['current'] = trans('tools.yourAgeYMD', ['y' => $dob->y, 'm' => $dob->m, 'd' => $dob->d]);
        $results['months'] = trans('tools.yourAgeMD', ['m' => $from->diffInMonths($to), 'd' => $dob->d]);
        $results['weeks'] = trans('tools.yourAgeW', ['w' => $from->diffInWeeks($to)]);
        $results['days'] = trans('tools.yourAgeD', ['d' => $from->diffInDays($to)]);
        $results['hours'] = trans('tools.yourAgeH', ['t' => $from->diffInHours($to)]);
        $results['minutes'] = trans('tools.yourAgeM', ['t' => $from->diffInMinutes($to)]);
        $results['seconds'] = trans('tools.yourAgeS', ['t' => $from->diffInSeconds($to)]);

        if ($tool->is_home == 1 && empty($request->route()->parameters)) {
            $relevant_tools = Tool::with('translations')->withCount('usageToday')->with('category')->active()->take('16')->get();
            $tool->index_content = str_replace("[x-tool-form]", $this->toolForm($tool, $results), $tool->index_content);

            $plans = Plan::active()
                ->with('properties')
                ->with('translations')
                ->get();

            $faqs = Faqs::active()->get();
            $properties = Property::active()->with('translations')->get();

            return view('tools.pages.age-calculator', compact('results', 'tool', 'year', 'month', 'day', 'year2', 'month2', 'day2', 'plans', 'faqs', 'properties', 'relevant_tools'));
        }

        return view('tools.age-calculator', compact('tool', 'results', 'year', 'month', 'day', 'year2', 'month2', 'day2'));
    }

    public function index(Tool $tool, $relevant_tools, $plans, $faqs, $properties)
    {
        $tool->index_content = str_replace("[x-tool-form]", $this->toolForm($tool), $tool->index_content);

        return view('tools.pages.age-calculator', compact('tool', 'relevant_tools', 'plans', 'properties', 'faqs'));
    }

    public function toolForm($tool, $results = null)
    {
        return view('tools.pages.forms.age-calculator', compact('tool', 'results'))->render();
    }

    public function indexContent()
    {
        $data = '<div class="raw-html-embed"><div class="age-calculater-page"><div class="banner-area">
        <div class="container">
          <div class="row">
            <div class="col-md-12 align-self-center text-center">
              <h1>Progress is impossible without content.</h1>
              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In malesuada posuere metus
                a sollicitudin. Nam a felis tellus. Sed tempor at quam sed feugiat. Nunc sodales vel
                purus vel euismod.</p>
              <div class="mt-3">
                <button type="button" class="btn btn-primary rounded-pill mb-2">Go Pro</button>
                <button type="button" class="btn btn-outline-primary rounded-pill mb-2">Try it free</button>
              </div>
            </div>
          </div>
        </div>
      </div>
      [x-tool-form]
      <div class="easy-calculate">
        <div class="container">
          <div class="row">
            <div class="col-md-4">
              <img class="w-100" src="/themes/canvas/images/easy-calculate-img.svg" alt="image">
            </div>
            <div class="col-md-8 align-self-center">
              <div class="hero-title bold">
                <h1>Easy calculate your age</h1>
              </div>
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
      </div></div>';
        return $data;
    }
}
