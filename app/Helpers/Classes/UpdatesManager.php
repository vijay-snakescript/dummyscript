<?php

namespace App\Helpers\Classes;

use Theme;
use Setting;
use ZipArchive;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Database\Seeders\UpdateToolSeeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;
use Jackiedo\DotenvEditor\Facades\DotenvEditor;

class UpdatesManager
{
    protected $register_endpoint = '';
    protected $check_updates = '';
    protected $update_links = '';
    protected $patch_links = '';
    protected $product = 'monster-tools';
    protected $download_url = 'monster-tools';

    /**
     * @param array
     */
    protected $verifyData;

    /**
     * @var Update Version
     */
    private $version = '2.0.0';

    /**
     * @param DotEnvEditor $dotEnvEditor
     * @param Setting      $setting
     */
    public function __construct()
    {
        $this->verifyData = config('artisan.installed') ? [
            'code' => setting('purchase_code'),
            'version' => setting('version'),
            'item'  => $this->product,
            'return_uri'  => url('/'),
        ] : [
            'code' => '',
            'version' => $this->version,
            'item'  => $this->product,
            'return_uri'  => url('/'),
        ];
    }

    public function runUpdate()
    {
        $success = session('success');
        $error = session('error');

        $this->optimizeApp();
        $this->runMigration();
        $this->rebuildTheme();
        $this->UpdateAppVersion();
        $this->optimizeApp();
        $this->disableUpdateAlert();

        Session::flash('success', $success);
        Session::flash('error', $error);
    }

    public function getPatch()
    {
        try {
            $response = Http::post($this->patch_links, $this->verifyData);
            $jsonData = $response->json();

            if (isset($jsonData['status']) && !$jsonData['status']) {
                $message = $this->getMessage($jsonData);
                Session::flash('error', $message);

                return false;
            }

            $this->download_url = $jsonData['download'];

            return $this->downloadPatch($this->download_url);
        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage());

            return false;
        }

        return true;
    }

    /**
     * Get new app version.
     *
     * @return string
     */
    private function disableUpdateAlert()
    {
        Setting::set("update_available", 0);
        Setting::set("update_available_msg", null);

        Setting::save();
    }

    /**
     * Get new app version.
     *
     * @return string
     */
    private function getAppVersion()
    {
        return $this->version;
    }

    /**
     * Rebulid themes
     * Re-build Theme Cache
     *
     * @return void
     */
    public function rebuildTheme()
    {
        Theme::rebuildCache();
    }

    /**
     * Run the database migration.
     *
     * @return void
     */
    public function runMigration()
    {
        Artisan::call('migrate', ['--force' => true]);
        if (version_compare(setting('version'), '1.4.0', '<')) {
            Artisan::call("db:seed", ['--class' => 'HomepageSeeder', '--force' => true]);
        }

        if (file_exists(base_path('database/seeders/UpdateToolSeeder.php'))) {
            Artisan::call("db:seed", ['--class' => 'UpdateToolSeeder', '--force' => true]);
        }
    }

    /**
     * Update app settings and env
     *
     * @return string
     */
    private function UpdateAppVersion()
    {
        $version = $this->getAppVersion();

        $env = DotenvEditor::load();
        $env->setKey('APP_VERSION', $version);
        $env->setKey('LOG_CHANNEL', 'daily');
        $env->save();

        Setting::set('homepage_favorite_tools', 1);
        Setting::set('disable_favorite_tools', 1);
        Setting::set('disable_auth', 0);
        Setting::set('unlimited_usage', 0);
        Setting::set('version', $version);
        Setting::save();
    }

    /**
     * Clear Cache and Optimize App
     *
     * @return string
     */
    private function optimizeApp()
    {
        Artisan::call('optimize:clear');
        Artisan::call('optimize');
    }

    /**
     * check for the updates
     *
     * @return string
     */
    public function checkUpdates()
    {
        $response = Http::post($this->check_updates, ['version' => setting('version'), 'item'  => $this->product, 'return_uri' => url('/')]);
        $jsonData = $response->json();

        // updates are found now asking user to download/verify
        if (isset($jsonData['status']) && $jsonData['status'] === true) {
            Session::flash('update_button', $jsonData['message']);
            Setting::set("update_available", 1);
            Setting::set("update_available_msg", $jsonData['message']);
            Setting::save();
        } else {
            $this->disableUpdateAlert();
        }

        $this->checkPatches();

        return true;
    }

    /**
     * check for the patches
     *
     * @since v2.0.0
     */
    public function checkPatches()
    {
        try {
            $response = Http::post($this->patch_links, $this->verifyData);
            $jsonData = $response->json();

            Cache::forever('patches-available', $jsonData);
        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage());

            return false;
        }
    }

    public function validateAndPerformUpdate()
    {
        try {
            $response = Http::post($this->update_links, $this->verifyData);
            $jsonData = $response->json();

            if (isset($jsonData['status']) && !$jsonData['status']) {
                $message = $this->getMessage($jsonData);
                Session::flash('error', $message);

                return false;
            }

            $this->download_url = $jsonData['download'];
            if ($jsonData['has_requirements'] == true) {
                return $this->checkRequirements($jsonData['requirements']);
            } else {
                return $this->downloadUpdates($this->download_url);
            }
        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage());

            return false;
        }

        return true;
    }


    public function downloadUpdates($url)
    {
        $path = $this->downloadUpdateZip($url);
        if (!file_exists($path)) {
            Session::flash('error', "Failed to download update, please try again later.");

            return false;
        }

        $zip = new ZipArchive;
        if (!$zip) {
            Session::flash('error', "PHP Zip extension not loaded.");

            return false;
        }
        $zip->open($path);

        $zip->extractTo(base_path());
        $zip->close();
        @unlink($path);

        if (file_exists(resource_path('cleanup.php'))) {
            include_once(resource_path('cleanup.php'));
        }
        $this->runUpdate();

        Session::flash('success', 'Application updated successfully');

        return true;
    }

    public function downloadPatch($url)
    {
        $path = $this->downloadUpdateZip($url);
        if (!file_exists($path)) {
            Session::flash('error', "Failed to download patch, please try again later.");

            return false;
        }

        $zip = new ZipArchive;
        if (!$zip) {
            Session::flash('error', "PHP Zip extension not loaded.");

            return false;
        }

        if ($zip->open($path) !== TRUE) {
            Session::flash('error', "An error occurred creating your ZIP file.");

            return false;
        }

        $zip->extractTo(base_path());
        $zip->close();
        @unlink($path);

        $this->runUpdate();

        Session::flash('success', 'Patch applied successfully');

        return true;
    }

    protected function getMessage($response)
    {
        if (isset($response['errors']) && is_array($response['errors']) && count($response['errors']) !== 0) {
            $error = array_pop($response['errors']);
            if (is_array($error)) {
                $error = array_pop($error);
            }
            return $error;
        }

        return $response['message'] ?? 'System could not perform update.';
    }

    protected function downloadUpdateZip($url)
    {
        set_time_limit(0);
        try {
            $path = base_path('tmp.zip');
            //This is the file where we save the    information
            $fp = fopen($path, 'w+');
            //Here is the file we are downloading, replace spaces with %20
            $ch = curl_init(str_replace(" ", "%20", $url));
            // make sure to set timeout to a high enough value
            // if this is too low the download will be interrupted
            curl_setopt($ch, CURLOPT_TIMEOUT, 600);
            // send post data
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($this->verifyData));
            // write curl response to file
            curl_setopt($ch, CURLOPT_FILE, $fp);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            // get curl response
            curl_exec($ch);
            curl_close($ch);
            fclose($fp);
        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage());

            return false;
        }

        return $path;
    }


    public function checkRequirements($requirements_link)
    {
        $response = Http::post($requirements_link, $this->verifyData);

        $jsonData = $response->json();
        if ($jsonData['status'] == true) {
            $requiremens  = $jsonData['message']['requirements'];
            $requirements =  $this->loadSystemRequirments($requiremens);
            if ($requirements['status'] === true) {
                return $this->downloadUpdates($this->download_url);
            } else {
                Session::flash('error', $requirements['message']);
            }
        }

        return false;
    }


    private function loadSystemRequirments($requiremens)
    {
        $validationStatus = true;
        $validationErrors = array();

        if (isset($requiremens['php']['version']) && !version_compare(PHP_VERSION, $requiremens['php']['version'], $requiremens['php']['operator'])) { //checking php version
            $validationStatus = false;
            array_push($validationErrors, "PHP Version must be {$requiremens['php']['operator']} {$requiremens['php']['version']}");
        }

        // Check extensions requirements
        if (is_array($requiremens['extensions'])) {
            foreach ($requiremens['extensions'] as $key => $value) {
                if (extension_loaded($key) != $value) {
                    $validationStatus = false;
                    array_push($validationErrors, "{$key}: {$value}");
                }
            }
        }

        // Check defined requirements
        if (is_array($requiremens['defined'])) {
            foreach ($requiremens['defined'] as $key => $value) {
                if (defined($key) != $value) {
                    $validationStatus = false;
                    array_push($validationErrors, $key);
                }
            }
        }

        // Check Apache requirements
        if (is_array($requiremens['apache']) && strpos($_SERVER['SERVER_SOFTWARE'], 'Apache') !== false) {
            foreach ($requiremens['apache'] as $key => $value) {
                if (!in_array('mod_rewrite', apache_get_modules()) && $value == true) {
                    $validationStatus = false;
                    array_push($validationErrors, $key);
                }
            }
        }

        return ['message' => __("New update requirements are not met :errors", ['errors' => implode(', ', $validationErrors)]), 'status' => $validationStatus];
    }
}
