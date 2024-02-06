<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tag;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use App\Models\Language;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PostRequest;

class PostsController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('q', false);
        $posts = Post::query()
            ->when(!empty($search), function ($query) use ($search) {
                $query->search($search, null, true);
            })
            ->with(['translations', 'author', 'tags.translations', 'categories.translations', 'media'])
            ->paginate();

        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        $locales = Language::getLocales();
        $users = User::all();
        $categories = Category::with('translations')->post()->get();
        $tags = Tag::with('translations')->get()->map(function ($tag) {
            return [
                'id' => $tag->id,
                'value' => $tag->name,
            ];
        });

        return view('posts.create', compact('locales', 'users', 'categories', 'tags'));
    }

    public function store(PostRequest $request)
    {
        $post = Post::create($request->only('status', 'author_id'));
        $langs = Language::getLocales();
        foreach ($langs as $lang) {
            $translation = $request->only($lang->locale);
            if ($translation[$lang->locale]['title']) {
                $post->fill($translation);
            }
        }
        $post->save();

        // attach media
        if ($request->hasFile("featured_image")) {
            $post->addMediaFromRequest("featured_image")->toMediaCollection('featured-image');
        }

        $tags = $this->prepareTags($request);
        $post->tags()->sync($tags);
        $post->categories()->sync($request->categories);

        return redirect()->route('admin.posts')->withSuccess(__('admin.postCreated'));
    }

    protected function prepareTags(Request $request)
    {
        $tagsInput = $request->input('tags', '[]');
        $tagsInput = json_decode($tagsInput);
        $tags = [];
        if ($tagsInput) {
            foreach ($tagsInput as $tag) {
                if (isset($tag->id)) {
                    $tags[] = $tag->id;
                } else {
                    $tag_name = trim($tag->value);
                    if (empty($tag_name)) {
                        continue;
                    }
                    $tag = Tag::whereTranslation('name', $tag_name)->first();
                    if (!$tag) {
                        $tag = Tag::create(
                            [
                                'name'      => $tag_name,
                                'title'      => $tag_name,
                                'slug'      => Str::slug($tag_name),
                                'status'      => 1,
                            ]
                        );
                    }
                    $tags[] = $tag->id;
                }
            }
        }

        return $tags;
    }

    public function edit(Request $request, Post $post)
    {
        $post->load('media');
        $locales = Language::getLocales();
        $users = User::all();
        $categories = Category::with('translations')->post()->get();
        $tags = Tag::with('translations')->get()->map(function ($tag) {
            return [
                'id' => $tag->id,
                'value' => $tag->name,
            ];
        });

        return view('posts.edit', compact('locales', 'post', 'users', 'categories', 'tags'));
    }

    /**
     *
     */
    public function update(PostRequest $request, Post $post)
    {
        $post->update($request->only('published', 'author_id'));
        $langs = Language::getLocales();
        foreach ($langs as $lang) {
            $translation = $request->only($lang->locale);
            if ($translation[$lang->locale]['title']) {
                $post->fill($translation);
            }
        }
        $post->save();

        // attach media
        if ($request->hasFile("featured_image")) {
            $post->clearMediaCollection("featured-image");
            $post->addMediaFromRequest("featured_image")->toMediaCollection('featured-image');
        }

        $tags = $this->prepareTags($request);
        $post->tags()->sync($tags);
        $post->categories()->sync($request->categories);

        return redirect()->route('admin.posts')->withSuccess(__('admin.postUpdated'));
    }

    public function destroy(Post $post)
    {
        $post->forceDelete();

        return redirect()->route('admin.posts')->withSuccess(__('admin.postDeleted'));
    }

    public function featured($post, $id)
    {
        $post = Post::find($post);

        if ($id != $post->featured) {
            $post->featured = $id;
        } else if ($id == $post->featured) {
            $post->featured = 0;
        }
        $post->save();
        return redirect()->route('admin.posts')->withSuccess(__('admin.postUpdated'));
    }
}
