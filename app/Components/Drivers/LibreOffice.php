<?php

namespace App\Components\Drivers;

use App\Models\Tool;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use App\Contracts\ToolDriverInterface;
use Illuminate\Support\Facades\Storage;
use App\Helpers\Classes\LibreOfficeWrapper;

class LibreOffice implements ToolDriverInterface
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
        $page_size = $request->input('page_size', null);
        $merge = (bool) $request->input('merge', true);
        $margin = $request->input('margin', null);
        $orientation = $request->input('page_orientation', null);
        $format = $request->input('format', 'pdf');
        $filter = $request->input('filter', null);
        $options = $request->input('options', []);

        $process_id = (string) Str::uuid();
        $files = Cache::remember($process_id, job_cache_time(), function () use ($files, $filter, $format, $options, $process_id) {
            $uploadedFiles = collect();
            $bin_path = !empty($this->tool->settings->libre_office_path) ? escapeshellarg($this->tool->settings->libre_office_path) : null;
            $libreoffice = new LibreOfficeWrapper($bin_path);
            if (!empty($options) && count($options) > 0) {
                $libreoffice->setOption(...$options);
            }
            $libreoffice->useFilter($filter);

            $output = storage_path("app/public/temp/{$process_id}");
            Storage::disk('public')->makeDirectory("temp/{$process_id}");

            foreach ($files as $file) {
                $file_url = tempFileUpload($file, false, false, $process_id);

                $libreoffice->setOutputPath($output);
                $libreoffice->addFile(storage_path('app/' . $file_url['path']));
                $uploadedFiles->push($file_url);
            }

            $converted = $libreoffice->convert($format);

            $results = $converted->map(function ($file) use ($process_id) {
                $file['url'] = url('storage/temp/' . $process_id) . '/' . $file['filename'];

                return $file;
            });

            return $results;
        });

        return compact('process_id', 'files', 'success');
    }
}
