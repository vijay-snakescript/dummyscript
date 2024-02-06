<?php

namespace App\Components\Drivers;

use App\Models\Tool;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use App\Contracts\ToolDriverInterface;
use Illuminate\Support\Facades\Storage;
use App\Helpers\Classes\GhostscriptWrapper;

class GhostscriptDriver implements ToolDriverInterface
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
        $convert_to = $request->input('convert_to', false);
        $device = $request->input('device', 'pdfwrite');
        $arguments = $request->input('arguments', []);
        $output = $request->input('output', 'pdf');
        $outputFilename = $request->input('filename', null);
        $page_size = $request->input('page_size', null);
        $page_orientation = $request->input('page_orientation', null);
        $merge = $request->input('merge_pages', null) == 1;
        $margins = $request->input('margin', null);
        $dpi = $request->input('dpi', 72);
        $grayscale = (bool) $request->input('grayscale', false);

        $files = Cache::remember($process_id . Str::random(4), job_cache_time(), function () use ($files, $pagesData, $device, $dpi, $page_size, $margins, $page_orientation, $outputFilename, $merge, $arguments, $convert_to, $output, $process_id, $grayscale) {
            Storage::disk('public')->makeDirectory("temp/{$process_id}");
            Storage::disk('local')->makeDirectory("temp/{$process_id}");

            $uploadedFiles = collect();
            $resizeImage = null;
            $bin_path = !empty($this->tool->settings->gs_bin_path) ? escapeshellarg($this->tool->settings->gs_bin_path) : "gs";
            $process = new GhostscriptWrapper($bin_path, $process_id);
            $output_dir = storage_path('app/public/temp/' . $process_id);

            foreach ($arguments as $index => $argument) {
                $process->setArgument($argument);
            }

            if (!empty($outputFilename)) {
                $process->setOutputFilename($outputFilename);
            }

            if (!empty($page_size)) {
                $process->setOutputPaperSize($page_size);
                if ($page_size == 'A4') {
                    $width = (int) (11.69 * $dpi);
                    $height = (int) (8.27 * $dpi);
                    $resizeImage = $page_orientation == 'landscape' ? [$width, $height] : [$height, $width];
                } else if ($page_size == 'Letter') {
                    $width = (int) (11 * $dpi);
                    $height = (int) (8.5 * $dpi);
                    $resizeImage = $page_orientation == 'landscape' ? [$width, $height] : [$height, $width];
                }
            }

            foreach ($files as $index => $file) {
                $pageData = $pagesData->where('index', $index)->first();
                $file_url = $convert_to === false ? tempFileUpload($file, false, false, $process_id) : tempFileUploadConverter($file, $convert_to, false, false, $process_id, $resizeImage);

                $uploadedFiles->push($file_url);
                $file_path = storage_path('app/' . $file_url['path']);
                $password = $pageData['password'] ?? null;
                $rotation = $pageData['rotation'] ?? null;
                $isImage = $convert_to !== false;

                $process->addFile($file_path, null, $rotation, $password, $isImage);
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
            if ($grayscale) {
                $process->setOutputGrayscale();
            }

            $result = $process
                ->setDrvice($device)
                ->setMargins($margins)
                ->setDPI($dpi)
                ->setOutputDirectory($output_dir)
                ->setOutputFormat($output)
                ->convert($merge);

            return $result;
        });

        return compact('process_id', 'files', 'success');
    }

    public function parsePages($request)
    {
        $success = true;
        $files = $request->file('files');
        $pagesData = collect(json_decode($request->input('fileData'), true));
        $process_id = (string) Str::uuid();
        $convert_to = $request->input('convert_to', false);
        $device = $request->input('device', 'pdfwrite');
        $arguments = $request->input('arguments', []);
        $output = $request->input('output', 'pdf');
        $outputFilename = $request->input('filename', null);
        $page_size = $request->input('page_size', null);
        $page_orientation = $request->input('page_orientation', null);
        $merge = $request->input('merge_pages', null) == 1;
        $margins = $request->input('margin', null);
        $dpi = $request->input('dpi', null);
        $grayscale = (bool) $request->input('grayscale', false);

        $files = Cache::remember($process_id, job_cache_time(), function () use ($files, $pagesData, $device, $dpi, $page_size, $margins, $page_orientation, $outputFilename, $merge, $arguments, $convert_to, $output, $process_id, $grayscale) {
            Storage::disk('public')->makeDirectory("temp/{$process_id}");
            Storage::disk('local')->makeDirectory("temp/{$process_id}");

            $uploadedFiles = collect();
            $resizeImage = null;
            $bin_path = !empty($this->tool->settings->gs_bin_path) ? escapeshellarg($this->tool->settings->gs_bin_path) : "gs";
            $process = new GhostscriptWrapper($bin_path, $process_id);
            $output_dir = storage_path('app/public/temp/' . $process_id);
            $isImage = $convert_to !== false;

            foreach ($arguments as $index => $argument) {
                $process->setArgument($argument);
            }

            if (!empty($outputFilename)) {
                $process->setOutputFilename($outputFilename);
            }

            if (!empty($page_size)) {
                $process->setOutputPaperSize($page_size);
                if ($page_size == 'A4') {
                    $width = (int) (11.69 * $dpi);
                    $height = (int) (8.27 * $dpi);
                    $resizeImage = $page_orientation == 'landscape' ? [$width, $height] : [$height, $width];
                } else if ($page_size == 'Letter') {
                    $width = (int) (11 * $dpi);
                    $height = (int) (8.5 * $dpi);
                    $resizeImage = $page_orientation == 'landscape' ? [$width, $height] : [$height, $width];
                }
            }

            foreach ($files as $index => $file) {
                $file_url = $convert_to === false ? tempFileUpload($file, false, false, $process_id) : tempFileUploadConverter($file, $convert_to, false, false, $process_id, $resizeImage);
                $file_url['full_path'] = storage_path('app/' . $file_url['path']);

                $uploadedFiles->push($file_url);
            }
            // dd($pagesData);
            foreach ($pagesData as $page) {
                $file = $uploadedFiles[$page['index']];
                $process->addFile($file['full_path'], $page['page'], $page['rotation'], $page['password'], $isImage);
            }

            if ($grayscale) {
                $process->setOutputGrayscale();
            }

            if ($dpi) {
                $process->setDPI($dpi);
            }

            $result = $process
                ->setDrvice($device)
                ->setMargins($margins)
                ->setOutputDirectory($output_dir)
                ->setOutputFormat($output)
                ->convert($merge);

            return $result;
        });

        return compact('process_id', 'files', 'success');
    }
}
