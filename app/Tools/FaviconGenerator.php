<?php

namespace App\Tools;

use App\Models\Tool;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use ZanySoft\Zip\Facades\Zip;
use App\Contracts\ToolInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FaviconGenerator implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        return view('tools.favicon-generator', compact('tool'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $validated = $request->validate([
            'image' => 'required|image|mimes:png,jpeg,gif,jpg'
        ]);
        $image = $request->file('image');
        $process_id = (string) Str::uuid();
        $size_arry = ["android-icon-36" => '36', "android-icon-48" => '48', "android-icon-192" => '192', "apple-icon-57" => '57', "apple-icon-60" => '60', "apple-icon-72" => '72', "apple-icon-76" => '76', "apple-icon-120" => '120', "apple-icon-152" => '152', "apple-icon-180" => '180', "favicon-16" => '16', "favicon-32" => '32', "favicon-96" => '96', "ms-icon-70" => '70', "ms-icon-144" => '144', "ms-icon-150" => '150', "ms-icon-310" => '310'];
        $uploadedFiles = collect();
        foreach ($size_arry as $key => $size) {
            $image_resized = Image::make($image)->resize($size, $size)->encode($image->getClientOriginalExtension());
            $filename = "{$key}x{$size}." . $image->getClientOriginalExtension();
            $resource = UploadedFile::fake()->createWithContent($filename, $image_resized);
            $url = tempFileUpload($resource, false, false, $process_id);
            if ($url) {
                $uploadedFiles->push($url);
            }
        }

        $file = Cache::remember($process_id, job_cache_time(), function () use ($uploadedFiles) {
            $cache_array = [];
            $cache_array['disk'] = $uploadedFiles[0]['disk'];
            $cache_array['path'] = pathinfo($uploadedFiles[0]['path'])['dirname'];

            return $cache_array;
        });

        $results = [
            'process_id' => $process_id
        ];

        return view('tools.favicon-generator', compact('results', 'tool'));
    }

    public function postAction(Request $request, $tool)
    {
        return $this->downloadAllImages($request, $tool);
    }

    protected function downloadAllImages($request, $tool)
    {
        $process_id = $request->process_id;
        // Get last cached resource
        $result = Cache::get($process_id);
        // Make path for all images
        $job = Storage::disk($result['disk'])->path($result['path']);

        // Zip store location & path.
        $storeDisk = Storage::disk(config('artisan.temporary_files_disk', 'local'));
        $storePath = config('artisan.temporary_files_path', 'temp') . DIRECTORY_SEPARATOR . 'compressed' . DIRECTORY_SEPARATOR;
        $storeDisk->makeDirectory($storePath);
        $zip = Zip::create($storeDisk->path($storePath) . "{$process_id}.zip");
        $zip->add($job, true);
        $zip->close();

        return $storeDisk->download($storePath . "{$process_id}.zip", "{$tool->slug}.zip");
    }

    protected function processSingleFile($request)
    {
        $validator = Validator::make($request->all(), [
            'process_id' => 'required|uuid',
            'file' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => __('tools.invalidRequest')]);
        }
        $process_id = $request->input('process_id');
        $filename = $request->input('file');

        // Fetch Job
        $job = Cache::get($process_id);
        if (!$job) {
            return response()->json(['status' => false, 'message' => __('tools.theRequestExpired')]);
        }

        // Find image in job
        $file = collect($job)->firstWhere('original_filename', $filename);
        if (!$file) {
            return response()->json(['status' => false, 'message' => __('tools.theRequestExpired')]);
        }

        $result = tempFileUploadToImageConverter($file, 'jpg', true, false, $process_id);
        Cache::put($process_id . "-download-all", $result, job_cache_time());

        return response()->json([
            'success' => true,
            'filename' => $result['original_filename'],
            'size' => $result['size'],
            'url' => url($result['url']),
        ]);
    }
}
