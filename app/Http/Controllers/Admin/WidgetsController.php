<?php

namespace App\Http\Controllers\Admin;

use App\Models\Widget;
use App\Models\WidgetArea;
use Illuminate\Http\Request;
use App\Helpers\Facads\Widgets;
use App\Http\Controllers\Controller;

class WidgetsController extends Controller
{
    protected $widget_namespaces;

    public function __construct()
    {
        $this->widget_namespaces = Widgets::all();
        $this->middleware('permission:view widgets')->only("index");
        $this->middleware('permission:edit widgets')->only("edit", "update");
        $this->middleware('permission:delete widgets')->only("destroy");
    }

    /**
     * Display a listing of the resource.
     *
     * @PermissionAnnotation(name="Admin Management - Widgets Management")
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sidebars = WidgetArea::with('widgets')->orderBy('order')->get();
        $widgets = array();

        foreach ($this->widget_namespaces as $widget) {
            if (!method_exists($widget, 'build')) {
                continue;
            }

            $widgets[] = $widget;
        }

        return view('widgets.index', compact('sidebars', 'widgets'));
    }

    /**
     * Store sorted widgets in database.
     *
     * @PermissionAnnotation(name="Admin Management - Sort Widget Action")
     *
     * @return \Illuminate\Http\Response
     */
    public function sort(Request $request)
    {
        if ($request->wantsJson()) {
            $request->validate(['ids' => 'required']);

            $reload = false;
            $success = false;
            $response = array();

            $ids = $request->input('ids');
            foreach ($ids as $index => $id) {
                $order = $index + 1;
                $widget = Widget::findOrFail($id);
                $widget->order = $order;
                $widget->save();
            }
            $success = true;

            return response()->json(['success' => $success, 'response' => $response, 'reload' => $reload]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @PermissionAnnotation(name="Admin Management - Store Widget Action")
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->wantsJson()) {
            $request->validate([
                'area' => 'required',
                'areaId' => 'required|exists:widget_areas,id',
                'widget' => 'required|in:' . $this->widget_namespaces->implode(',')
            ]);

            $reload = false;
            $success = false;
            $response = array();

            $area = $request->input('area');
            $widget_area_id = $request->input('areaId');
            $name = $request->input('widget');
            $order = (Widget::Where('widget_area_id', $widget_area_id)->count()) + 1;

            $widget = Widget::create([
                'widget_area_id' => $widget_area_id,
                'name'           => $name,
                'order'          => $order,
                'status'         => 1,
                'web'            => 1,
                'mobile'         => 1,
                'ajax'           => 1
            ]);

            $success = true;
            $response['message'] = __('widgets.addedSuccessfully');
            $response['html'] = app($name)->build($area, $widget)?->render();

            return response()->json(['success' => $success, 'toggle' => '#wa-' . $area, 'widget' => '#wa-' . $area . ' .sortable-widgets-wrapper', 'response' => $response, 'reload' => $reload]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @PermissionAnnotation(name="Admin Management - Edit Widget Action")
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($request->wantsJson()) {
            $reload = false;
            $success = false;
            $response = array();

            $widget = Widget::findOrFail($id);
            if ($widget) {
                $widget->title = $request->input('title');
                $widget->settings = $request->input('settings');
                $widget->web = $request->input('web', 0);
                $widget->mobile = $request->input('mobile', 0);
                $widget->status = $request->input('status', 0);
                $widget->ajax = $request->input('ajax', 0);
                $widget->save();
                $success = true;
                $response['message'] = __('widgets.updatedSuccessfully');
            }

            return response()->json(['success' => $success, 'response' => $response, 'reload' => $reload]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @PermissionAnnotation(name="Admin Management - Delete Widget Action")
     *
     * @param  \App\Models\Widget $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        if ($request->wantsJson()) {
            $reload = false;
            $success = false;
            $response = array();

            $widget = Widget::findOrFail($id);
            if ($widget) {
                $success = true;
                $response['message'] = __('widgets.deletedSuccessfully');
                $widget->delete();
            }

            return response()->json(['success' => $success, 'response' => $response, 'reload' => $reload]);
        }
    }
}
