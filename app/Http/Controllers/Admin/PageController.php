<?php

namespace App\Http\Controllers\Admin;

use App\Models\Page;
use App\Models\Language;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\PageRequest;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('q', false);

        $pages = Page::query();
        if (!empty($search)) {
            $pages->search($search, null, true);
        }
        $pages = $pages->paginate();

        return view('pages.index', compact('pages'));
    }

    public function create()
    {
        $locales = Language::getLocales();

        return view('pages.create', compact('locales'));
    }

    public function store(PageRequest $request)
    {
        $page = Auth::user()->pages()->create($request->only('published'));
        $langs = Language::getLocales();
        foreach ($langs as $lang) {
            $translation = $request->only($lang->locale);
            if ($request->file("{$lang->locale}.og_image")) {
                if ($image = fileUpload($request->file("{$lang->locale}.og_image"))) {
                    $translation['og_image'] = $image;
                }
            }

            if ($translation[$lang->locale]['title']) {
                $page->fill($translation);
            }
        }
        $page->save();

        return redirect()->route('admin.pages')->withSuccess(__('admin.pageCreated'));
    }

    public function edit(Request $request, Page $page)
    {
        $locales = Language::getLocales();

        return view('pages.edit', compact('locales', 'page'));
    }

    /**
     *
     */
    public function update(PageRequest $request, Page $page)
    {
        $langs = Language::getLocales();
        foreach ($langs as $lang) {
            $translation = $request->only($lang->locale);
            if ($request->file("{$lang->locale}.og_image")) {
                if ($image = fileUpload($request->file("{$lang->locale}.og_image"))) {
                    $translation['og_image'] = $image;
                }
            } else {
                unset($translation[$lang->locale]['og_image']);
            }

            if ($translation[$lang->locale]['title']) {
                $page->fill($translation);
            }
        }
        $page->save();

        return redirect()->route('admin.pages')->withSuccess(__('admin.pageUpdated'));
    }

    public function destroy(Page $page)
    {
        $page->forceDelete();

        return redirect()->route('admin.pages')->withSuccess(__('admin.pageDeleted'));
    }
}
