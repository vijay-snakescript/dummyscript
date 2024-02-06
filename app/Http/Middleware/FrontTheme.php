<?php

namespace App\Http\Middleware;

use Theme;
use Closure;
use Illuminate\Http\Request;
use Butschster\Head\Facades\Meta;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class FrontTheme
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Config::get('artisan.installed')) {
            $theme = Config::get('artisan.front_theme', 'canvas');
            $this->setTheme($theme);
        }

        if (session()->has('locale')) {
            App::setLocale(session()->get('locale'));
        }

        return $next($request);
    }

    public function setTheme($themeName)
    {
        if (Theme::exists($themeName)) {
            Theme::set($themeName);

            $path = "css/{$themeName}-css.css";
            $css_file_name = "{$themeName}-css.css";

            if (Storage::disk('public')->exists($path) && $themeName == Config::get('artisan.front_theme')) {
                $dynamic_css_url = Storage::disk('public')->url($path);

                Meta::addStyle($css_file_name, $dynamic_css_url);
            }
        }
    }
}
