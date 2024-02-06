<?php

namespace App\Components\Drivers;

use App\Models\Tool;
use Illuminate\Support\Str;
use Spatie\Browsershot\Browsershot;
use App\Contracts\ToolDriverInterface;
use Illuminate\Support\Facades\Storage;
use Spatie\Browsershot\Exceptions\CouldNotTakeBrowsershot;
use Symfony\Component\Process\Exception\ProcessFailedException;

class DefaultScreenshotDriver implements ToolDriverInterface
{
    private $tool;

    public function __construct(Tool $tool)
    {
        $this->tool = $tool;
    }

    public function parse($request)
    {
        $url = $request->input('url');
        $type = $request->input('type', 'desktop');

        $filename = "screenshot-" . time() . ".png";

        $path = config('artisan.temporary_files_path', 'temp') . '/' . date('m') . '/';
        $disk = config('artisan.public_files_disk', 'public');
        Storage::disk($disk)->makeDirectory($path);

        $filepath = Storage::disk($disk)->path($path . $filename);
        $error = false;
        try {
            $browser = Browsershot::url($url)
                ->setOption('args', ['--disable-web-security'])
                ->waitUntilNetworkIdle(true)
                ->ignoreHttpsErrors()
                // ->userDataDir(storage_path('app/browsershot'))
                // ->noSandbox()
                // ->addChromiumArguments([
                //     'disable-extensions'
                // ])
                ->fullPage();

            if (isset($this->tool->settings->phantomjs_node_module_path) && !empty($this->tool->settings->phantomjs_node_module_path)) {
                $browser->setNodeModulePath($this->tool->settings->phantomjs_node_module_path);
            }

            if (isset($this->tool->settings->phantomjs_node_path) && !empty($this->tool->settings->phantomjs_node_path)) {
                $browser->setNodeBinary($this->tool->settings->phantomjs_node_path);
            }

            if (isset($this->tool->settings->phantomjs_npm_path) && !empty($this->tool->settings->phantomjs_npm_path)) {
                $browser->setNpmBinary($this->tool->settings->phantomjs_npm_path);
            }

            if (isset($this->tool->settings->phantomjs_chrome_path) && !empty($this->tool->settings->phantomjs_chrome_path)) {
                $browser->setChromePath($this->tool->settings->phantomjs_chrome_path);
            }

            if ($type == 'desktop') {
                $browser->windowSize(1920, 1080);
            }

            if ($type == 'mobile') {
                $browser->userAgent(setting('screenshot_tool_agent', 'Mozilla/5.0 (iPhone; CPU iPhone OS 11_0 like Mac OS X) AppleWebKit/604.1.38 (KHTML, like Gecko) Version/11.0 Mobile/15A372 Safari/604.1'))
                    ->windowSize(375, 812)
                    ->mobile()
                    ->touch();
            }
            $browser->save($filepath);
        } catch (ProcessFailedException $e) {
            $error = __('common.somethingWentWrong');
        } catch (CouldNotTakeBrowsershot $e) {
            $error = $e->getMessage();
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

        if ($error) {
            return [false, $error];
        }

        $image = Storage::disk($disk)->url($path . $filename);

        return [true, $image];
    }
}
