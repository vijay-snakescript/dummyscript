<?php

namespace App\Http\Controllers;

use Exception;
use App\Install\App;
use App\Install\Database;
use App\Install\Requirement;
use App\Install\AdminAccount;
use App\Install\VerifyPurchase;
use Illuminate\Routing\Controller;
use App\Http\Requests\InstallRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Jackiedo\DotenvEditor\Facades\DotenvEditor;

class InstallController extends Controller
{
    public function preInstallation(Requirement $requirement)
    {
        $route = Route::current()->getName();

        return view('install.pre_installation', compact('requirement', 'route'));
    }

    public function verifyPurchase(Requirement $requirement, VerifyPurchase $verifyPurchase)
    {
        $route = Route::current()->getName();

        if (!$requirement->satisfied()) {
            return redirect('/install/pre-installation');
        }

        return view('install.purchase', compact('requirement', 'verifyPurchase', 'route'));
    }

    public function redirectPurchase(VerifyPurchase $verifyPurchase)
    {
        return $verifyPurchase->login();
    }

    public function returnPurchase(VerifyPurchase $verifyPurchase)
    {
        return $verifyPurchase->authorize();
    }

    public function getConfiguration(Requirement $requirement, VerifyPurchase $verifyPurchase)
    {
        $route = Route::current()->getName();

        if (!$requirement->satisfied()) {
            return redirect('/install/pre-installation');
        }

        if (!$verifyPurchase->satisfied()) {
            return redirect('/install/verify');
        }

        return view('install.configuration', compact('requirement', 'verifyPurchase', 'route'));
    }

    public function postConfiguration(
        InstallRequest $request,
        Database $database,
        AdminAccount $admin,
        App $app
    ) {
        @set_time_limit(0);

        try {
            $database->setup($request->db);
            $admin->setup($request->admin);
            $app->setup($request->website);
            $this->optimizeApp();
        } catch (Exception $e) {
            return back()->withInput()->withErrors($e->getMessage());
        }

        return redirect('/install/complete');
    }

    public function complete(Requirement $requirement, VerifyPurchase $verifyPurchase)
    {
        $route = Route::current()->getName();

        if (config('artisan.installed')) {
            return redirect()->route('front.index');
        }

        if (!$requirement->satisfied()) {
            return redirect('/install/pre-installation');
        }

        if (!$verifyPurchase->satisfied()) {
            return redirect('/install/verify');
        }

        DotenvEditor::setKey('APP_INSTALLED', 'true')->save();

        $this->optimizeApp();

        return view('install.complete', compact('route'));
    }

    private function optimizeApp()
    {
        Cache::flush();
        Artisan::call('optimize');
    }
}
