<?php

namespace App\Tools;

use App\Models\Tool;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use ZanySoft\Zip\Facades\Zip;
use App\Contracts\ToolInterface;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class PdfToZip implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        return view('tools.pdf-to-zip', compact('tool'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $validated = $request->validate([
            'files' => "required|max:{$tool->no_file_tool}",
            'files.*' => "required|mimes:pdf|max:" . convert_mb_into_kb($tool->fs_tool)
        ]);

        $process_id = Str::uuid()->toString();
        $results = ['process_id' => $process_id];
        $results['files'] = Cache::remember($process_id, job_cache_time(), function () use ($request, $process_id, $tool) {
            $files = $request->file('files');
            $storeDisk = Storage::disk(config('artisan.public_files_disk', 'public'));
            $storePath = config('artisan.temporary_files_path', 'temp') . DIRECTORY_SEPARATOR . 'zip' . DIRECTORY_SEPARATOR . $process_id;
            $storeDisk->makeDirectory($storePath);
            $zipPath = $storeDisk->path("{$storePath}/{$tool->slug}.zip");
            $zip = Zip::create($zipPath);

            foreach ($files as $file) {
                $upload = tempFileUpload($file, false, false, $process_id);
                $zip->add(Storage::disk($upload['disk'])->path($upload['path']), false);
            }

            $zip->close();

            return [[
                'filename' => File::basename($zipPath),
                'url' => url($storeDisk->url("{$storePath}/{$tool->slug}.zip")),
                'size' => File::size($zipPath),
            ]];
        });

        return view('tools.pdf-to-zip', compact('tool', 'results'));
    }
}
