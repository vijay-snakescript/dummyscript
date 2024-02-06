<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Butschster\Head\Facades\Meta;
use Illuminate\Support\Facades\Route;
use Diglactic\Breadcrumbs\Breadcrumbs;

class CategoryController extends Controller
{
    public function show(Request $request, $category)
    {
        $category = Category::tool()
            ->with(['translations', 'tools' => function ($q) {
                $q->active()->orderBy('display')->with('translations');
            }])
            ->active()
            ->slug($category)
            ->firstOrFail();

        Meta::setMeta($category);
        Breadcrumbs::setCurrentRoute(Route::current()->getName(), $category);

        return view('category.tools', compact('category'));
    }
}
