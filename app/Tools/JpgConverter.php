<?php

namespace App\Tools;

use App\Models\Tool;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use ZanySoft\Zip\Facades\Zip;
use App\Contracts\ToolInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class JpgConverter implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        return view('tools.jpg-converter', compact('tool'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $validated = $request->validate([
            'images' => "required|max:{$tool->no_file_tool}",
            'images.*' => "image|mimes:png,jpeg,gif,tif,webp,tiff|max:" . $tool->fs_tool * 1024
        ]);
        $images = $request->file('images');
        $process_id = (string) Str::uuid();

        $files = Cache::remember($process_id, job_cache_time(), function () use ($images) {
            $uploadedFiles = collect();
            foreach ($images as $image) {
                $file = tempFileUpload($image);
                if ($file) {
                    $uploadedFiles->push($file);
                }
            }

            return $uploadedFiles;
        });

        $results = [
            'files' => $files,
            'process_id' => $process_id
        ];

        return view('tools.jpg-converter', compact('results', 'tool'));
    }

    public function postAction(Request $request, $tool)
    {
        $action = $request->action;

        switch ($action) {
            case 'process-file':
                $image = $this->processSingleFile($request);
                return $image;
                break;
            case 'download-all':
                return $this->downloadAllImages($request, $tool);
                break;
        }

        abort(404);
    }

    protected function downloadAllImages($request, $tool)
    {
        $process_id = $request->process_id;
        // Get last cached resource
        $result = Cache::get($process_id . "-download-all");
        // Make path for all images
        $path = pathinfo($result['path'])['dirname'];
        $job = Storage::disk($result['disk'])->path($path);

        // Zip store location & path.
        $storeDisk = Storage::disk(config('artisan.temporary_files_disk', 'local'));
        $storePath = config('artisan.temporary_files_path', 'temp') . DIRECTORY_SEPARATOR . $process_id;
        $storeDisk->makeDirectory($storePath);
        $zip = Zip::create($storeDisk->path("{$storePath}/{$tool->slug}.zip"));
        $zip->add($job, true);
        $zip->close();

        return $storeDisk->download("{$storePath}/{$tool->slug}.zip");
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
