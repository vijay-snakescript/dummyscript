<?php

namespace App\Http\Controllers\Admin;

use App;
use App\Models\Language;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Artisan;
use Jackiedo\DotenvEditor\Facades\DotenvEditor;

class SystemController extends Controller
{
    /**
     * @PermissionAnnotation(name="System: Link storage")
     */
    public function storage(Request $request)
    {
        if ($request->ajax() && $request->wantsJson()) {
            Artisan::call('storage:link');
            $exitCode = Artisan::output();

            return response()->json(['success' => true, 'message' => $exitCode]);
        }

        App::abort(404);
    }

    /**
     * @PermissionAnnotation(name="System: Clear activity logs")
     */
    // public function clearLogs(Request $request)
    // {
    //     if ($request->ajax() && $request->wantsJson()) {
    //         Artisan::call('activitylog:clean', ['--days' => '0']);
    //         $exitCode = Artisan::output();

    //         return response()->json(['success' => true, 'message' => $exitCode]);
    //     }

    //     App::abort(404);
    // }

    /**
     * @PermissionAnnotation(name="System: Optimize Application")
     */
    public function optimize(Request $request)
    {
        if ($request->ajax() && $request->wantsJson()) {
            Artisan::call('optimize');

            return response()->json(['success' => true, 'message' => 'Files cleared & cached successfully!']);
        }

        App::abort(404);
    }

    /**
     * @PermissionAnnotation(name="System: Clear cache")
     */
    public function cache(Request $request)
    {
        if ($request->ajax() && $request->wantsJson()) {
            Artisan::call('cache:clear');
            $exitCode = Artisan::output();

            return response()->json(['success' => true, 'message' => $exitCode]);
        }

        App::abort(404);
    }

    /**
     * @PermissionAnnotation(name="System: Rebuild/Generate languages")
     */
    public function rebuild(Request $request)
    {
        if ($request->ajax() && $request->wantsJson()) {
            $locales = $this->getSubDirectories();
            $all = $added = $updated = $invalid = array();
            $def_flag = false;

            foreach ($locales as $locale => $language) {
                if (Lang::hasForLocale('front.name', $locale) && Lang::hasForLocale('front.direction', $locale) && Lang::hasForLocale('front.default', $locale)) {
                    $name = Lang::get('front.name', [], $locale);
                    $def = Lang::get('front.default', [], $locale);

                    $language = Language::firstOrNew(['locale' => $locale]);
                    $language->name = $name;
                    $language->is_default = $def === 'yes' && !$def_flag ? 1 : 0;
                    $was_available = ($language->id);
                    $language->save();

                    if (!$def_flag) {
                        $def_flag = $def === 'yes';
                    }

                    if (!$was_available) {
                        $added[] = $language->id;
                    } else {
                        $updated[] = $language->id;
                    }
                    $all[] = $language->id;
                } else {
                    $invalid[] = $locale;
                }
            }

            if (!$def_flag) {
                $language = Language::first();
                $language->is_default = 1;
                $language->save();
            }

            // Delete all other languages
            Language::whereNotIn('id', $all)->delete();

            // Update env
            $default = Language::where('is_default', 1)->first();
            $locales = Language::pluck('locale')->toArray();
            if ($default) {
                $env = DotenvEditor::load();
                $env->setKey('APP_LOCALE', $default->locale);
                $env->setKey('APP_FALLBACK_LOCALE', $default->locale);
                $env->setKey('ARTISAN_LOCALES', implode(',', $locales));
                $env->save();
            }

            return response()->json(['success' => true, 'message' => __('settings.languagesRegenderated', ['invalid' => count($invalid), 'added' => count($added), 'updated' => count($updated)])]);
        }

        App::abort(404);
    }


    /**
     * @PermissionAnnotation(name="System: Clear view cache")
     */
    public function view(Request $request)
    {
        if ($request->ajax() && $request->wantsJson()) {
            Artisan::call('view:clear');
            $exitCode = Artisan::output();

            return response()->json(['success' => true, 'message' => $exitCode]);
        }

        App::abort(404);
    }

    /**
     * @PermissionAnnotation(name="System: clear routes cache")
     */
    public function route(Request $request)
    {
        if ($request->ajax() && $request->wantsJson()) {
            Artisan::call('route:clear');
            $exitCode = Artisan::output();

            return response()->json(['success' => true, 'message' => $exitCode]);
        }

        App::abort(404);
    }

    /**
     * @PermissionAnnotation(name="ignore")
     */
    public function getSubDirectories()
    {
        $dir = App::langPath() . '/*';
        $directories = glob($dir);

        $subDir = array();
        foreach ($directories as $directory) {
            if (!pathinfo($directory, PATHINFO_EXTENSION)) {
                $locale = basename($directory);
                $subDir[$locale] = $directory;
            }
        }

        return $subDir;
    }
}
