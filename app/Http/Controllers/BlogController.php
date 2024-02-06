<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Butschster\Head\Facades\Meta;
use Illuminate\Support\Facades\Route;
use Diglactic\Breadcrumbs\Breadcrumbs;

class BlogController extends Controller
{
    public function index()
    {
        $posts = Post::published()
            ->latest()
            ->with('translations')
            ->with(['categories', 'author', 'media', 'tags'])
            ->paginate();

        Breadcrumbs::setCurrentRoute(Route::current()->getName(), $posts);

        $meta = __("static_pages.blog");
        Meta::setPaginationLinks($posts)->setMeta((object) $meta);

        return view('blog.show', compact('posts'));
    }

    public function show(Request $request, $slug)
    {
        $post = Post::with('translations')->published()->slug($slug)->firstOrFail();
        record_page_visit($post);

        Meta::setMeta($post, true);
        Breadcrumbs::setCurrentRoute(Route::current()->getName(), $post);

        return view('posts.show', compact('post'));
    }

    public function category($category)
    {
        $category = Category::slug($category)->with('translations')->active()->firstOrFail();
        $posts = Post::whereHas('categories', function ($query) use ($category) {
            $query->where('id', $category->id);
        })
            ->with('translations')
            ->with(['categories', 'author', 'media'])
            ->published()
            ->latest()
            ->paginate();

        Breadcrumbs::setCurrentRoute(Route::current()->getName(), $category);
        Meta::setPaginationLinks($posts)->setMeta($category);

        return view('blog.category', compact('posts', 'category'));
    }

    public function tag($tag)
    {
        $tag = Tag::slug($tag)->with('translations')->active()->firstOrFail();
        $posts = Post::whereHas('tags', function ($query) use ($tag) {
            $query->where('id', $tag->id);
        })
            ->with('translations')
            ->with(['categories', 'author', 'media'])
            ->published()
            ->paginate();

        Breadcrumbs::setCurrentRoute(Route::current()->getName(), $tag);
        Meta::setPaginationLinks($posts)->setMeta($tag);

        return view('blog.tag', compact('posts', 'tag'));
    }
}
