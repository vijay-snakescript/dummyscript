<?php

namespace App\Components\Drivers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use App\Contracts\ToolDriverInterface;
use thiagoalessio\TesseractOCR\TesseractOCR;

class TesseractOCRDriver implements ToolDriverInterface
{
    public function parse($file)
    {
        $process_id = (string) Str::uuid();

        list($text, $filename) = Cache::remember($process_id, job_cache_time(), function () use ($file) {
            $image =  $file->move(storage_path('temp'), $file->getClientOriginalName());
            $text = (new TesseractOCR($image->getRealPath()))->txt()->run();

            return [$text, pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '-' . time()];
        });

        return compact('text', 'filename', 'process_id');
    }
}
