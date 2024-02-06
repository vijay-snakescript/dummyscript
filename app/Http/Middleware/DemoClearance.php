<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;

class DemoClearance
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Config::get('artisan.installed')) {
            return $next($request);
        }

        $user = Auth::user();
        if ((Route::is('admin.*.update')
                || Route::is('admin.*.store')
                || Route::is('admin.*.destroy')
                || Route::is('admin.*.status.change')
                || Route::is('system.*')
                || Route::is('admin.menus.add-items')
                || Route::is('admin.role.action')
                || Route::is('update.*')
                || Route::is('admin.widgets.sort')
                || Route::is('admin.faqs.changeStatus')
                || Route::is('admin.*.status.change')
                || Route::is('user.*.update')
                || Route::is('user.twofactor.disable')
                || Route::is('user.deleteAccount.action'))
            && Auth::check() && ($user->email == 'admin@demo.com' || $user->email == 'user@demo.com')
        ) {
            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => __('Feature disabled in demo.')]);
            }

            return redirect()->back()->withError(__('Feature disabled in demo.'));
        }

        return $next($request);
    }
}
