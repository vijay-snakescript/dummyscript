<?php

namespace App\Components\Drivers;

use App\Models\Tool;
use Intervention\Image\Facades\Image;
use App\Contracts\ToolDriverInterface;
use Illuminate\Support\Facades\Storage;

class ThumioScreenshotDriver implements ToolDriverInterface
{
    private $tool;
    private $endpoint = 'https://image.thum.io/get/fullpage/png';
    private $desktop = '/viewportWidth/1920/width/1920/';
    private $mobile = '/iphoneX/';


    public function __construct(Tool $tool)
    {
        $this->tool = $tool;
        if (!empty($this->tool->settings->thumio_key)) {
            $this->endpoint .= "/auth/{$tool->settings->thumio_key}";
        }
    }

    public function parse($request)
    {
        $url = $request->input('url');
        $type = $request->input('type', 'desktop');

        $endpoint = $this->endpoint . ($type == 'desktop' ? $this->desktop : $this->mobile) . "?url={$url}";
        $filename = "screenshot-" . time() . ".png";
        $path = config('artisan.temporary_files_path', 'temp') . '/' . date('m') . '/';
        $disk = config('artisan.public_files_disk', 'public');
        Storage::disk($disk)->makeDirectory($path);

        $error = false;
        try {
            $img = Image::make($endpoint)->encode('png', 5);
            Storage::disk($disk)->put($path . $filename, $img->__toString());
            $image = Storage::disk($disk)->url($path . $filename);
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

        if ($error) {
            return [false, $error];
        }

        return [true, $image];
    }
}
