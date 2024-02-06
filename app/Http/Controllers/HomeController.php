<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use App\Models\Category;
use Illuminate\Http\Request;
use Butschster\Head\Facades\Meta;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Classes\UpdatesManager;

class HomeController extends ToolController
{
    public function home()
    {
        $tool = Tool::with('translations')->withCount('usageToday')->with('category')->index()->active()->first();

        if ($tool && class_exists($tool->class_name) && method_exists($tool->class_name, 'index') && method_exists($tool->class_name, 'handle')) {
            $tool->load('category');
            $instance = new $tool->class_name();
            $tool->createVisitLog(auth()->user());
            $relevant_tools = Tool::with('translations')->withCount('usageToday')->with('category')->active()->take('16')->orderBy('display')->get();
            Meta::setMeta($tool);

            return  $instance->index($tool, $relevant_tools);
        }
        Meta::setMeta();

        return $this->tools();
    }

    public function tools()
    {
        $favorties = Auth::check() ? Auth::user()->favorite_tools : null;
        $tools = Category::query()
            ->active()
            ->tool()
            ->with('translations')
            ->with(['tools' => function ($query) {
                $query->active()->with('translations')->orderBy('display');
            }])
            ->orderBy('order')
            ->get();

        $ads = ['above-tool', 'above-form', 'below-form', 'above-result', 'below-result'];
        Meta::setMeta();

        return view('index', compact('tools', 'ads', 'favorties'));
    }

    public function homeTool(Request $request)
    {
        $tool = Tool::with('translations')->where('is_home', true)->active()->firstOrFail();
        Meta::setMeta($tool);

        return $this->handle($request, $tool->slug);
    }
}
