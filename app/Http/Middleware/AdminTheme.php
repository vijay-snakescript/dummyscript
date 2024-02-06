<?php

namespace App\Http\Middleware;

use Theme;
use Closure;
use Setting;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use App\Helpers\Classes\UpdatesManager;

class AdminTheme
{

    /**
     * Set admin's default theme
     */
    protected $defaultTheme = 'admin';


    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $theme = Config::get('artisan.installed') ? Setting::get('admin_theme', $this->defaultTheme) : 'admin';
        $this->setTheme($theme);

        if (session()->has('locale')) {
            App::setLocale(session()->get('locale'));
        }

        return $next($request);
    }

    public function setTheme($themeName)
    {
        if (Theme::exists($themeName)) {
            Theme::set($themeName);
        }
    }

    public function setLocale()
    {
        $locale = Config::get('artisan.installed') ? Setting::get('default_locale', 'en') : 'en';

        if ($locale && $locale != app()->getLocale() && Lang::hasForLocale('common.title', $locale)) {
            App::setLocale($locale);
        }
    }
}
