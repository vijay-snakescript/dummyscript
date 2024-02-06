<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LangController extends Controller
{
    public function changeLocale(Request $request, $locale)
    {
        if (\Lang::hasForLocale('common.title', $locale)) {
            App::setLocale($locale);

            session()->put('locale', $locale);
            $menus = Menu::get();
            foreach ($menus as $menu) {
                app('\App\Models\Menu')->removeMenuFromCache($menu);
            }

            return redirect()->back();
        }

        return redirect()->back()->with('error', __('Language not found.'));
    }
}
