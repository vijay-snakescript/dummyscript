<?php

namespace App\Traits;

use Illuminate\Http\Request;
use ZanySoft\Zip\Facades\Zip;
use Illuminate\Support\Facades\Storage;

trait ToolsPostAction
{
    public function postAction(Request $request, $tool)
    {
        $process_id = $request->process_id;

        $storeDisk = Storage::disk(config('artisan.temporary_files_disk', 'local'));
        $storePath = config('artisan.temporary_files_path', 'temp') . DIRECTORY_SEPARATOR . 'zip' . DIRECTORY_SEPARATOR . $process_id;
        $storeDisk->makeDirectory($storePath);
        $zip = Zip::create($storeDisk->path("{$storePath}/{$tool->slug}.zip"));
        $zip->add(storage_path('app/public/temp/' . $process_id), true);
        $zip->close();

        return $storeDisk->download("{$storePath}/{$tool->slug}.zip");
    }
}
