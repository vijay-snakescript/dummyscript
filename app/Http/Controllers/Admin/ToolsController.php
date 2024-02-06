<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tag;
use App\Models\Tool;
use App\Models\Category;
use App\Models\Language;
use App\Models\Property;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ToolRequest;
use Illuminate\Support\Facades\Validator;

class ToolsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $locales = Language::getLocales();
        $search = $request->get('q', false);

        $tools = Tool::query()
            ->when(!empty($search), function ($query) use ($search) {
                $query->search($search, null, true);
            })
            ->with(['category', 'media', 'translations', 'views'])
            ->paginate();

        return view('tools.index', compact('locales', 'tools'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function homePage(Request $request)
    {
        $locales = Language::getLocales();
        $search = $request->get('q', false);

        $tools = Tool::query()
            ->when(!empty($search), function ($query) use ($search) {
                $query->search($search, null, true);
            })
            ->with(['category', 'media', 'translations', 'views'])
            ->get()
            ->filter(function ($tool) {
                return ($tool && class_exists($tool->class_name) && method_exists($tool->class_name, 'index'));
            });

        return view('tools.homepage', compact('locales', 'tools'));
    }

    /**
     *
     */
    public function edit(Request $request, Tool $tool)
    {
        $locales = Language::getLocales();
        $categories = Category::with('translations')->tool()->get();
        $tags = Tag::with('translations')->get();
        $properties = Property::active()->with('translations')->get();

        $instance = new $tool->class_name();
        $form_fields = [];
        if (method_exists($instance, 'getFileds')) {
            $form_fields = $instance->getFileds();
        }
        return view('tools.edit', compact('locales', 'tool', 'categories', 'tags', 'form_fields', 'properties'));
    }

    /**
     *
     */
    public function update(ToolRequest $request, Tool $tool)
    {
        $instance = new $tool->class_name();
        $form_fields = [];
        $settings_rules = [];
        if (method_exists($instance, 'getFileds')) {
            $form_fields = $instance->getFileds();
            foreach ($form_fields['fields'] as $field) {
                $settings_rules[$field['id']] = $field['validation'];
            }

            Validator::validate($request->input('settings'), $settings_rules);
        }

        if ($request->is_home == 1) {
            Tool::where('is_home', 1)->where('id', '!=', $tool->id)->update(['is_home' => 0]);
        }

        $tool->category()->sync([$request->category]);
        $tool->update($request->only(['slug', 'icon_type', 'icon_class', 'display', 'is_home', 'settings']));

        // attach media
        if ($request->hasFile("icon")) {
            $tool->clearMediaCollection("tool-icon");
            $tool->addMediaFromRequest("icon")->toMediaCollection('tool-icon');
        }

        $langs = Language::getLocales();
        foreach ($langs as $lang) {
            $translation = $request->only($lang->locale);
            if (!empty($translation[$lang->locale]['name'])) {
                if ($request->file("{$lang->locale}.og_image")) {
                    $tool->clearMediaCollection("{$lang->locale}-og-image");
                    $tool->addMediaFromRequest("{$lang->locale}.og_image")->toMediaCollection("{$lang->locale}-og-image");
                }
                unset($translation[$lang->locale]['og_image']);

                $tool->fill($translation);
            }
        }

        $properties = $tool->properties;
        if (isset($properties['properties']) && is_array($properties['properties'])) {
            foreach ($properties['properties'] as $property) {
                $key_guest = "property_{$property}_guest";
                $key_auth = "property_{$property}_auth";
                $properties['auth'][$property] = $request->$key_auth;
                $properties['guest'][$property] = $request->$key_guest;
            }
            $tool->properties = $properties;
        }
        $tool->save();

        return redirect()->back()->withSuccess(__('admin.toolUpdated'));
    }

    public function statusChange($id, $status)
    {
        $tool = Tool::find($id);
        $tool->update(['status' => $status]);

        return redirect()->route('admin.tools')->withSuccess(__('admin.toolUpdated'));
    }
}
