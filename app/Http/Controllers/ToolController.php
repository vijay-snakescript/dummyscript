<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Tool;
use Illuminate\Http\Request;
use Butschster\Head\Facades\Meta;
use App\Helpers\Classes\ArtisanApi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Illuminate\Support\Facades\Validator;

class ToolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $tool)
    {
        $tool = Tool::with('translations')->withCount('usageToday')->with('category')->slug($tool)->active()->firstOrFail();
        if (!class_exists($tool->class_name) && (!method_exists($tool->class_name, 'render') || !method_exists($tool->class_name, 'handle'))) {
            abort(404);
        }
        $instance = new $tool->class_name();

        record_page_visit($tool);
        Meta::setMeta($tool);
        Breadcrumbs::setCurrentRoute(Route::current()->getName(), $tool);

        return  $instance->render($request, $tool);
    }

    /**
     *
     */
    public function handle(Request $request, $tool)
    {
        try {
            if (!app(ArtisanApi::class)->hasRegistered()) {
                throw new \Exception("\120\x6c\x65\141\163\x65\40\162\145\x67\x69\x73\x74\145\162\40\171\157\165\x72\40\160\x75\162\143\150\141\163\x65\x2e");
            }
            $tool = Tool::with('translations')->withCount('usageToday')->slug($tool)->active()->firstOrFail();
            if (!class_exists($tool->class_name) && (!method_exists($tool->class_name, 'render') || !method_exists($tool->class_name, 'handle'))) {
                abort(404);
            }

            if (!$this->checkUsage($tool)) {
                return redirect()->back()->withErrors(__('tools.limitExceed'));
            }

            $tool->load('category');
            $instance = new $tool->class_name();
            $tool->createVisitLog(auth()->user());
            Breadcrumbs::setCurrentRoute(Route::current()->getName(), $tool);

            return  $instance->handle($request, $tool);
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    public function action(Request $request, $tool)
    {
        $tool = Tool::with('translations')->withCount('usageToday')->slug($tool)->active()->firstOrFail();
        if (!class_exists($tool->class_name) && (!method_exists($tool->class_name, 'render') || !method_exists($tool->class_name, 'handle'))) {
            abort(404);
        }

        if (!$this->checkUsage($tool)) {
            return redirect()->back()->withErrors(__('tools.limitExceed'));
        }
        $instance = new $tool->class_name();

        return $instance->action($request);
    }

    public function postAction(Request $request, $tool)
    {
        $tool = Tool::with('translations')->withCount('usageToday')->slug($tool)->active()->firstOrFail();
        if (!class_exists($tool->class_name) && (!method_exists($tool->class_name, 'render') || !method_exists($tool->class_name, 'handle'))) {
            abort(404);
        }
        if (!$this->checkUsage($tool)) {
            return redirect()->back()->withErrors(__('tools.limitExceed'));
        }
        $instance = new $tool->class_name();

        return $instance->postAction($request, $tool);
    }

    public function favouriteAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => __('tools.invalidRequest')]);
        }

        try {
            $user = Auth::user();
            $tool = Tool::find($request->id);
            $hasFavorited = $user->hasFavorited($tool);

            $hasFavorited ? $user->unfavorite($tool) : $user->favorite($tool);
            $message =  $hasFavorited ?  __('tools.favouriteRemoved') : __('tools.favouriteAdded');

            return response()->json(['success' => true, 'message' => $message]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function checkUsage($tool)
    {
        if (Auth::check() && Auth::user()->hasRole((int) config('artisan.super_admin_role', 'Super Admin')) || setting('unlimited_usage', 0) == 1) {
            return true;
        }

        if ($tool->du_tool && $tool->du_tool > 0 && $tool->du_tool  <= $tool->usage_today_count) {
            return false;
        }

        return true;
    }
}
