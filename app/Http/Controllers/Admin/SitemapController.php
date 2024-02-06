<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tag;
use App\Models\Page;
use App\Models\Post;
use App\Models\Tool;
use App\Models\Category;
use App\Http\Controllers\Controller;
use Spatie\Sitemap\SitemapGenerator;
use Illuminate\Support\Facades\Artisan;

class SitemapController extends Controller
{
    public function generate()
    {
        Artisan::call('optimize:clear');
        SitemapGenerator::create(config('app.url'))
            ->getSitemap()
            ->add(Tool::active()->with('translations')->get())
            ->add(Page::published()->with('translations')->get())
            ->add(Post::published()->with('translations')->get())
            ->add(Category::active()->with('translations')->get())
            ->add(Tag::active()->with('translations')->get())
            ->add(route('ads.remove'))
            ->add(route('plans.list'))
            ->add(route('contact'))
            ->writeToFile(public_path('sitemap.xml'));

        Artisan::call('optimize');

        return response()->json(['success' => true, 'message' => __('settings.sitemapGeneratedSuccessfully')]);
    }
}
