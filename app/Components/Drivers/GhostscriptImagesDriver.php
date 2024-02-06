<?php

namespace App\Components\Drivers;

use App\Models\Tool;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use App\Contracts\ToolDriverInterface;
use Illuminate\Support\Facades\Storage;
use App\Helpers\Classes\GhostscriptWrapper;

class GhostscriptImagesDriver implements ToolDriverInterface
{
    private $tool;

    public function __construct(Tool $tool)
    {
        $this->tool = $tool;
    }

    public function parse($request)
    {
        $success = true;
        $files = $request->file('files');
        $pagesData = collect(json_decode($request->input('fileData'), true));
        $process_id = (string) Str::uuid();

        $files = Cache::remember($process_id, job_cache_time(), function () use ($files, $pagesData, $process_id) {
            Storage::disk('public')->makeDirectory("temp/{$process_id}");
            Storage::disk('local')->makeDirectory("temp/{$process_id}");

            $uploadedFiles = collect();
            $bin_path = !empty($this->tool->settings->gs_bin_path) ? escapeshellarg($this->tool->settings->gs_bin_path) : '';
            $process = new GhostscriptWrapper($bin_path);
            $output_dir = storage_path('app/public/temp/' . $process_id);

            foreach ($files as $index => $file) {
                $pageData = $pagesData->where('index', $index);
                $file_url = tempFileUploadToImageConverter($file, 'jpg', false, false, $process_id);

                $uploadedFiles->push($file_url);
                $file_path = storage_path('app/' . $file_url['path']);
                $password = $pageData['password'] ?? null;

                $process->addFile($file_path, $password);
            }
            /**
             * PNG pngalpha (for transparent png), png48 (for 48 bit image), pnggray (for gray scale image), pngmono (for monochrome image)
             * BMP bmp32b, bmpgray (for gray scale)
             * JPEG jpeg (24 bits), jpegcmyk (32 bits), jpeggray (gray scale)
             * TIFF tiff24nc (24 bits), tiff32nc (32 bits), tiff48nc (48 bits), tiff64nc (64 bits), tiffgray (gray scale)
             *
             * For image Resolution use setArgument and pass: -r300 (for 300 DPI), -r600 (for 600 DPI)
             *
             * txtwrite (extracts text from PDF)
             * docxwrite (converts to DOCX)
             *
             * pdfwrite (for PDF tasks)
             */
            $process
                ->setDrvice('pdfwrite')
                ->setOutputDirectory($output_dir)
                ->setOutputFormat('pdf')
                ->convert();

            /**
             * The wrapper just process the command and doesn't
             * generate files list, you can copy or impement
             * method in wrapper class to return output files list.
             *
             *
             */
        });

        return compact('process_id', 'files', 'success');
    }
}
