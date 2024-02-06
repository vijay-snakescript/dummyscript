<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use Butschster\Head\Facades\Meta;
use Illuminate\Support\Facades\Route;
use Diglactic\Breadcrumbs\Breadcrumbs;

class PageController extends Controller
{
    public function show(Request $request, $slug)
    {
        $page = Page::with('translations')->published()->slug($slug)->firstOrFail();

        Meta::setMeta($page);
        Breadcrumbs::setCurrentRoute(Route::current()->getName(), $page);

        return view('pages.index', compact('page'));
    }
}
