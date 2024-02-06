<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tag;
use App\Models\Language;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TagRequest;

class TagsController extends Controller
{
    public function index(Request $request)
    {
        $locales = Language::getLocales();
        $search = $request->get('q', false);

        $tags = Tag::withCount('posts')->with('translations');
        if (!empty($search)) {
            $tags->search($search, null, true);
        }
        $tags = $tags->paginate();

        return view('tags.index', compact('locales', 'tags'));
    }

    public function store(TagRequest $request)
    {
        $tag = Tag::create($request->only('status', true));

        $langs = Language::getLocales();
        foreach ($langs as $lang) {
            $translation = $request->only($lang->locale);
            if ($translation[$lang->locale]['name']) {
                $tag->fill($translation);
            }
        }
        $tag->save();

        return redirect()
            ->back()
            ->withSuccess(__('admin.tagCreated'));
    }

    public function edit(Request $request, Tag $tag)
    {
        $locales = Language::getLocales();
        $tags = Tag::withCount('posts');
        if (!empty($search)) {
            $tags->search($search, null, true);
        }
        $tags = $tags->paginate();

        return view('tags.index', compact('locales', 'tags', 'tag'));
    }

    /**
     *
     */
    public function update(TagRequest $request, Tag $tag)
    {
        $langs = Language::getLocales();
        foreach ($langs as $lang) {
            $translation = $request->only($lang->locale);

            if ($translation[$lang->locale]['name']) {
                $tag->fill($translation);
            }
        }
        $tag->save();

        return redirect()->route('admin.tags')->withSuccess(__('admin.tagUpdated'));
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();

        return redirect()->back()->withSuccess(__('admin.tagDeleted'));
    }
}
