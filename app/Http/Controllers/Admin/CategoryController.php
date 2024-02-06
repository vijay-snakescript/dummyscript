<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\Language;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;

class CategoryController extends Controller
{
    public function index(Request $request, $type = null)
    {
        $locales = Language::getLocales();
        $search = $request->get('q', false);

        $categories = Category::query()
            ->when(!empty($search), function ($query) use ($search) {
                $query->search($search, null, true);
            })
            ->parents()
            ->with(['posts', 'tools'])
            ->with('translations')
            ->with(['children' => function ($query)  use ($type) {
                $query->where('type', $type);
            }])
            ->when($type, function ($query) use ($type) {
                $query->where('type', $type);
            })
            ->orderBy('order')
            ->paginate();

        $parents = Category::active()
            ->with('translations')
            ->when($type, function ($query) use ($type) {
                $query->where('type', $type);
            })
            ->parents()
            ->orderBy('order')
            ->get();

        $isChildAllowed = $type == "tool" ? 1 : 0;

        return view('categories.index', compact('locales', 'categories', 'parents', 'type', 'isChildAllowed'));
    }

    public function sort(Request $request)
    {
        $categories = $request->input('order');
        if (is_array($categories)) {
            foreach ($categories as $order => $catgory) {
                Category::tool()->where('id', $catgory)->update(['order' => ($order + 1)]);
            }
        }
    }

    public function store(CategoryRequest $request)
    {
        $category = Category::create([
            'status' => $request->input('status', true),
            'parent' => $request->parent,
            'type' => $request->input('type', "post")
        ]);

        $langs = Language::getLocales();
        foreach ($langs as $lang) {
            $translation = $request->only($lang->locale);
            if ($translation[$lang->locale]['name']) {
                $category->fill($translation);
            }
        }
        $category->save();

        return redirect()
            ->route('admin.categories', ['type' => $category->type])
            ->withSuccess(__('admin.cateogryCreated'));
    }

    public function edit(Request $request, Category $category)
    {
        $type = $category->type;
        $locales = Language::getLocales();
        $categories = Category::withCount('posts', 'tools')
            ->parents()
            ->with('translations')
            ->with(['children' => function ($query)  use ($type) {
                $query->where('type', $type);
            }])->when($type, function ($query) use ($type) {
                $query->where('type', $type);
            })
            ->paginate();

        $parents = Category::active()
            ->with('translations')
            ->when($type, function ($query) use ($type) {
                $query->where('type', $type);
            })
            ->parents()
            ->get();

        $isChildAllowed = $type == "tool" ? 1 : 0;

        return view('categories.index', compact('locales', 'parents', 'categories', 'category', 'isChildAllowed'));
    }

    /**
     *
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $category->update([
            'parent' => $request->parent,
        ]);

        $langs = Language::getLocales();
        foreach ($langs as $lang) {
            $translation = $request->only($lang->locale);
            if ($translation[$lang->locale]['name']) {
                $category->fill($translation);
            }
        }
        $category->save();

        return redirect()->route('admin.categories', ['type' => $category->type])->withSuccess(__('admin.categoryUpdated'));
    }


    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('admin.categories', ['type' => $category->type])->withSuccess(__('admin.categoryDeleted'));
    }
}
