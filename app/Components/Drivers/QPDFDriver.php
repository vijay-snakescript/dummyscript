<?php

namespace App\Components\Drivers;

use App\Models\Tool;
use Illuminate\Support\Str;
use App\Helpers\Classes\QpdfWrapper;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use App\Contracts\ToolDriverInterface;
use Illuminate\Support\Facades\Storage;

class QPDFDriver implements ToolDriverInterface
{
    private $tool;

    public function __construct(Tool $tool)
    {
        $this->tool = $tool;
    }

    public function parse($request)
    {
        $success = true;
        $binary = $this->tool->settings->bin_path ?? null;
        $pagesData = collect(json_decode($request->input('fileData'), true));
        $files = $request->file('files');
        $process_id = Str::uuid()->toString();

        $passwordField = $request->input('password', null);
        $page_size = $request->input('page_size', null);
        $margin = $request->input('margin', null);
        $orientation = $request->input('page_orientation', null);

        $files = Cache::remember($process_id, job_cache_time(), function () use ($binary, &$success, $pagesData, $files, $passwordField, $page_size, $margin, $orientation, $process_id) {
            $process = new QpdfWrapper($binary);
            $rotation = $this->formatPageRotation($pagesData);
            $filesData = $pagesData->groupBy('index');
            foreach ($filesData as $index => $data) {
                $file = $files[$index];
                $pageData = $pagesData->where('index', $index);
                $pages = $pageData->pluck('page')->toArray();
                $password = $pageData->first()['password'] ?? '';
                $upload = tempFileUpload($file, false, false, $process_id);
                $path = Storage::disk($upload['disk'])->path($upload['path']);
                $process->addFile($path, $pages, $password);
            }

            $filename = "{$this->tool->name}.pdf";
            $output = storage_path("app/public/temp/{$process_id}/{$filename}");
            Storage::disk('public')->makeDirectory("temp/{$process_id}");

            $process->setOutputFile($output)
                ->setLinearize()
                ->setOutputEncryption($passwordField)
                ->setPageMargins($margin)
                ->setPagesRotation($rotation)
                ->setPageOrientation($orientation)
                ->setPageSize($page_size);

            if ($process->execute()) {
                return [[
                    'filename' => $filename,
                    'url' => url("storage/temp/{$process_id}/{$filename}"),
                    'size' => File::size($output),
                ]];
            }
            $success = false;

            return $success;
        });

        return compact('process_id', 'files', 'success');
    }

    public function parseIndividually($request)
    {
        $binary = $this->tool->settings->bin_path ?? null;
        $pagesData = collect(json_decode($request->input('fileData'), true));
        $files = $request->file('files');
        $passwordField = $request->input('password', null);
        $page_size = $request->input('page_size', null);
        $margin = $request->input('margin', null);
        $orientation = $request->input('page_orientation', null);

        $process_id = Str::uuid()->toString();
        $files = Cache::remember($process_id, job_cache_time(), function () use ($binary, $pagesData, $files, $passwordField, $page_size, $margin, $orientation, $process_id) {
            $process = new QpdfWrapper($binary);
            $rotation = $this->formatPageRotation($pagesData);
            $resultFiles = collect();

            foreach ($files as $index => $file) {
                $pageData = $pagesData->where('index', $index);
                $pages = $pageData->pluck('page')->toArray();
                $password = $pageData->first()['password'] ?? '';
                $upload = tempFileUpload($file, false, false, $process_id);
                $path = Storage::disk($upload['disk'])->path($upload['path']);
                $process->addFile($path, $pages, $password);

                $filename = $upload['filename'];
                $output = storage_path("app/public/temp/{$process_id}/{$filename}");
                Storage::disk('public')->makeDirectory("temp/{$process_id}");

                $process->setOutputFile($output)
                    ->setLinearize()
                    ->setOutputEncryption($passwordField)
                    ->setPagesRotation($rotation)
                    ->setPageMargins($margin)
                    ->setPageOrientation($orientation)
                    ->setPageSize($page_size);

                if ($process->execute()) {
                    $resultFiles->push([
                        'filename' => $filename,
                        'url' => url("storage/temp/{$process_id}/{$filename}"),
                        'size' => File::size($output),
                    ]);
                }
            }

            return $resultFiles->count() == 0 ? false : $resultFiles->toArray();
        });

        return [
            'files' => $files,
            'process_id' => $process_id
        ];
    }

    private function formatPageRotation($pages)
    {
        $rotation = [];
        foreach ($pages as $index => $page) {
            if ($page['rotation'] != 0) {
                $rotation[($index + 1)] = $page['rotation'];
            }
        }

        return $rotation;
    }
}
