<?php

namespace App\Components\Drivers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use App\Contracts\ToolDriverInterface;
use Illuminate\Support\Facades\Storage;
use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer;


class ImageCompressorDriver implements ToolDriverInterface
{
    public function parse($request)
    {
        $process_id = $request->input('process_id');
        $filename = $request->input('file');
        $message = null;
        // Fetch Job
        $job = Cache::get($process_id);
        if (!$job) {
            $message = response()->json(['status' => false, 'message' => __('tools.theRequestExpired')]);
        }

        // Find image in job
        $file = collect($job)->firstWhere('original_filename', $filename);
        if (!$file) {
            $message = response()->json(['status' => false, 'message' => __('tools.theRequestExpired')]);
        }

        if (!empty($message)) {
            return ['status' => false, 'response' => $message];
        }

        // File found send it to tinypng for compression.
        // Image store path
        $path = config('artisan.temporary_files_path', 'temp') . DIRECTORY_SEPARATOR . $process_id . DIRECTORY_SEPARATOR;
        $disk = config('artisan.public_files_disk', 'public');
        $pathName = $path . $file['original_filename'];
        Storage::disk($disk)->makeDirectory($path);
        $pathToStore = Storage::disk($disk)->path($pathName);
        // Image Path
        $pathToImage = Storage::disk($file['disk'])->path($file['path']);
        ImageOptimizer::optimize($pathToImage, $pathToStore);

        $result = [
            'path' => $pathToStore,
            'size' => File::size($pathToStore),
            'original_size' => $file['size'],
            'original_filename' => $file['original_filename'],
            'url' => url(generateFileUrl($pathName, $disk)),
        ];

        if (!Cache::has($process_id . "-download-all")) {
            Cache::put($process_id . "-download-all", $result, job_cache_time());
        }

        return [
            'success' => true,
            'filename' => $result['original_filename'],
            'size' => $result['size'],
            'original_size' => $file['size'],
            'compression_ratio' => round((100 - ($result['size'] / $file['size'] * 100)), 2),
            'url' => url($result['url']),
        ];
    }
}
