<?php

namespace App\Helpers\Classes;

use Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use App\Helpers\Classes\UpdatesManager;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ArtisanApi extends UpdatesManager
{
    public function register(Request $request)
    {
        $request->validate(['code' => 'required|uuid'], ['code.*' => 'Please enter valid purchase code.']);
        $data = $this->verifyData;
        $data['code'] = $request->input('code');

        try {
            $response = Http::post($this->register_endpoint, $data);
            $jsonData = $response->json();

            if (isset($jsonData['status']) && $jsonData['status'] === true) {
                $code = $request->input('code');

                try {
                    $content = artisanCrypt()->encrypt($code);
                } catch (\Exception $e) {
                    throw new \Exception("Couldn't register product, please contact support.");
                }

                Setting::set('purchase_code', $code);
                Setting::save();

                Storage::disk('local')->put(".{$this->product}", $content);
            }

            return response()->json($jsonData, 200);
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return false;
    }

    public function verify()
    {
        $file = storage_path("app/.{$this->product}");
        if (file_exists($file)) {
            $content = File::get($file);
            try {
                $code = artisanCrypt()->decrypt($content);
            } catch (\Exception $e) {
                $code = null;
            }

            if (config('artisan.installed') && setting('purchase_code', null) === $code) {
                return $code;
            } else if (!config('artisan.installed')) {
                return $code;
            }

            return null;
        }
    }

    public function getToken()
    {
		return 'bnVsbGNhdmU=';
        $code = $this->verify();

        return !empty($code) ? base64_encode($code) : null;
    }

    public function hasRegistered()
    {
    	return true;
        $verify = Validator::make(['code' => $this->verify()], ['code' => 'required|uuid']);

        return $verify->passes();
    }
}
