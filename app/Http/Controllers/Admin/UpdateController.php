<?php

namespace App\Http\Controllers\Admin;

use Setting;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Helpers\Classes\ArtisanApi;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class UpdateController extends Controller
{
    protected $manager;
    protected $version;

    public function __construct(ArtisanApi $manager)
    {
        $this->manager = $manager;
        $this->version = setting('version');;
    }

    public function show()
    {
        return view('update');
    }

    public function patches()
    {
        $version = setting('version');
        $applied = setting("patches-" . Str::slug($version) . "-applied", '{}');
        $applied = json_decode($applied, true);
        $patches = Cache::get('patches-available', []);
        $patches = is_array($patches) && !isset($patches['status']) ? $patches : [];

        return view('patches', compact('patches', 'applied'));
    }

    public function applyPatches(Request $request, $id)
    {
        $download = collect(Cache::get('patches-available'))
            ->filter(function ($patch) use ($id) {
                return $id == $patch['id'];
            })
            ->map(function ($patch) {
                return $patch['download'];
            })
            ->first();

        if ($download && $this->manager->downloadPatch($download)) {
            $applied = setting("patches-" . Str::slug($this->version) . "-applied", '{}');
            $applied = json_decode($applied, true);

            if (!in_array($id, $applied)) {
                $applied[] = $id;
                Setting::set("patches-" . Str::slug($this->version) . "-applied", json_encode($applied));
                Setting::save();
            }
        }

        return redirect()->route('system.patches');
    }

    public function update()
    {
        $this->manager->runUpdate();

        return redirect()->route('admin.dashboard')->withSuccess('Application updated successfully!');
    }

    public function checkUpdates()
    {
        $this->manager->checkUpdates();

        return redirect()->route('admin.dashboard');
    }

    public function verifyUpdates()
    {
        if ($this->manager->validateAndPerformUpdate()) {
            return redirect()->route('system.update');
        }

        return redirect()->route('admin.dashboard');
    }
}
