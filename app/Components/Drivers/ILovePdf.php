<?php

namespace App\Components\Drivers;

use Exception;
use App\Models\Tool;
use Illuminate\Support\Str;
use ZanySoft\Zip\Facades\Zip;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;
use App\Contracts\ToolDriverInterface;
use Illuminate\Support\Facades\Storage;
use Ilovepdf\Ilovepdf as LovePdfWrapper;

class ILovePdf implements ToolDriverInterface
{
    private $tool;
    private $task = "officepdf";

    public function __construct(Tool $tool)
    {
        $this->tool = $tool;
        set_time_limit(0);
    }

    public function parse($request)
    {
        $success = true;
        if (!isset($this->tool->settings->love_pdf_public_id)) {
            $message = __('common.apiKeyNotProvided');
            $success = false;

            return compact('message', 'success');
        }

        if ($this->task == "split") {
            return $this->SplitTask($request);
        }

        if ($this->task == "imagepdf") {
            return $this->ImageToPDF($request);
        }

        if ($this->task == "pages") {
            return $this->parsePages($request);
        }

        if ($this->task == "pdfjpg") {
            return $this->pdfToImage($request);
        }

        $files = $request->file('files');
        $file_password = $request->input('password', null);
        $format = $request->input('format', 'pdf');
        $process_id = (string) Str::uuid();
        $pagesData = collect(json_decode($request->input('fileData'), true));
        $files = Cache::remember($process_id, job_cache_time(), function () use ($files, $pagesData, $format, $process_id, $file_password) {
            Storage::disk('public')->makeDirectory("temp/{$process_id}");
            Storage::disk('local')->makeDirectory("temp/{$process_id}");
            $ilovepdf = new LovePdfWrapper($this->tool->settings->love_pdf_public_id, $this->tool->settings->love_pdf_secret_key);
            $outputDir = storage_path("app/temp/{$process_id}/");
            $processedDir = storage_path("app/public/temp/{$process_id}/");

            $myTask = $ilovepdf->newTask($this->task);
            if (!empty($file_password)) {
                $myTask->setPassword($file_password);
            }

            if ($this->task == "compress") {
                $myTask->setCompressionLevel('recommended');
            }

            if (!in_array($this->task, ['officepdf'])) {
                $filesData = $pagesData->groupBy('index');
                foreach ($filesData as $index => $data) {
                    $file = $files[$index];
                    $pageData = $pagesData->where('index', $index)->first();
                    $uploadedFile = tempFileUpload($file, false, false, $process_id);
                    $uploadedPath = Storage::disk($uploadedFile['disk'])->path($uploadedFile['path']);
                    $taskFile = $myTask->addFile($uploadedPath);

                    if (!empty($pageData['password'])) {
                        $taskFile->setPassword($pageData['password']);
                    }
                }
            } else {
                foreach ($files as $index => $file) {
                    $pageData = $pagesData->where('index', $index)->first();
                    $uploadedFile = tempFileUpload($file, false, false, $process_id);
                    $uploadedPath = Storage::disk($uploadedFile['disk'])->path($uploadedFile['path']);
                    $taskFile = $myTask->addFile($uploadedPath);

                    if (!empty($pageData['password'])) {
                        $taskFile->setPassword($pageData['password']);
                    }
                }
            }

            $myTask->setOutputFilename("{$this->tool->name}.{$format}");
            $myTask->execute();
            $myTask->download(count($files) > 1 && !in_array($this->task, ['merge']) ? $outputDir : $processedDir);
            if (count($files) > 1 && !in_array($this->task, ['merge'])) {
                $is_valid = Zip::check("{$outputDir}output.zip");
                if (!$is_valid) {
                    throw new Exception(__('common.somethingWentWrong'));
                }

                $zip = Zip::open("{$outputDir}output.zip");
                $zip->extract("{$processedDir}");
                $results = [];
                foreach ($zip->listFiles() as $index => $file) {
                    $results[] = [
                        'filename' => $file,
                        'url' =>  url('storage/temp/' . $process_id . '/' . $file),
                        'size' => File::size("{$processedDir}{$file}"),
                    ];
                }
            } else {
                $file = "{$this->tool->name}.{$format}";
                $results[] = [
                    'filename' => $file,
                    'url' =>  url('storage/temp/' . $process_id . '/' . $file),
                    'size' => File::size("{$processedDir}{$file}"),
                ];
            }

            return $results;
        });

        return compact('process_id', 'files', 'success');
    }

    public function setTask($task)
    {
        $this->task = $task;
    }

    public function pdfToImage($request)
    {
        $success = true;
        $files = $request->file('files');
        $file_password = $request->input('password', null);
        $output = $request->input('output', null);
        $process_id = (string) Str::uuid();
        $pagesData = collect(json_decode($request->input('fileData'), true));
        $files = Cache::remember($process_id, job_cache_time(), function () use ($files, $pagesData, $output, $process_id, $file_password) {
            Storage::disk('public')->makeDirectory("temp/{$process_id}");
            Storage::disk('local')->makeDirectory("temp/{$process_id}");
            $ilovepdf = new LovePdfWrapper($this->tool->settings->love_pdf_public_id, $this->tool->settings->love_pdf_secret_key);
            $outputDir = storage_path("app/temp/{$process_id}/");

            $myTask = $ilovepdf->newTask($this->task);
            $myTask->setMode('pages');
            $myTask->setDpi(300);
            foreach ($files as $index => $file) {
                $pageData = $pagesData->where('index', $index)->first();
                $uploadedFile = tempFileUpload($file, false, false, $process_id);
                $uploadedPath = Storage::disk($uploadedFile['disk'])->path($uploadedFile['path']);
                $taskFile = $myTask->addFile($uploadedPath);
                if (!empty($file_password)) {
                    $taskFile->setPassword($pageData['password']);
                }
            }

            $myTask->execute();
            $myTask->download($outputDir);

            return $this->listOrConvertImages($myTask, $process_id, $output);;
        });

        return compact('process_id', 'files', 'success');
    }

    protected function listOrConvertImages($task, $process_id, $output = 'jpg')
    {
        $outputDir = storage_path("app/temp/{$process_id}/");
        $processedDir = storage_path("app/public/temp/{$process_id}/");
        $path = $outputDir . $task->result->download_filename;
        $is_zip = Zip::check($path);
        $files =  [];
        $results = collect();

        if ($is_zip) {
            $zip = Zip::open("{$outputDir}{$task->result->download_filename}");
            $zip->extract($output != 'jpg' ? $outputDir : $processedDir);
            $files = $zip->listFiles();
        } else {
            $file = $task->result->download_filename;
            $files = [$file];
            if ($output == 'jpg') {
                File::move($outputDir . $file, $processedDir . $file);
            }
        }

        foreach ($files as $file) {
            $path = $output != 'jpg' ? $outputDir . $file : $processedDir . $file;
            if (!File::isDirectory($path)) {
                $filename = File::name($path);
                if ($output != 'jpg') {
                    Image::make($path)->encode($output)->save($processedDir . $filename . '.' . $output);
                    $path = $processedDir . $filename . '.' . $output;
                }

                $size = File::size($path);
                $results->push([
                    'filename' => $filename . '.' . $output,
                    'url' =>  url('storage/temp/' . $process_id . '/' . $filename . '.' . $output),
                    'size' => $size,
                ]);
            }
        }

        return $results->toArray();
    }

    public function parsePages($request)
    {
        $success = true;
        $files = $request->file('files');
        $file_password = $request->input('password', null);

        $process_id = (string) Str::uuid();
        $pagesData = collect(json_decode($request->input('fileData'), true));
        $files = Cache::remember($process_id, job_cache_time(), function () use ($files, $pagesData, $process_id, $file_password) {
            Storage::disk('public')->makeDirectory("temp/{$process_id}");
            Storage::disk('local')->makeDirectory("temp/{$process_id}");
            $ilovepdf = new LovePdfWrapper($this->tool->settings->love_pdf_public_id, $this->tool->settings->love_pdf_secret_key);
            $processedDir = storage_path("app/public/temp/{$process_id}/");
            $uploadedFiles = collect();

            $myTask = $ilovepdf->newTask('split');
            $myTask->setMergeAfter(true);
            foreach ($files as $file) {
                $upload = tempFileUpload($file, false, false, $process_id);
                $upload['full_path'] = Storage::disk($upload['disk'])->path($upload['path']);
                $uploadedFiles->push($upload);
            }

            foreach ($pagesData as $page) {
                $myTask->setRanges($page['page']);
                $file = $uploadedFiles[$page['index']];
                $taskFile = $myTask->addFile($file['full_path']);
                if ($page['rotation'] > 0) {
                    $taskFile->setRotation($page['rotation']);
                }
                $taskFile->setPassword($page['password']);
            }
            $myTask->setOutputFilename("{$this->tool->name}");
            $myTask->execute();
            $myTask->download($processedDir);

            $file = "{$this->tool->name}.pdf";
            $results[] = [
                'filename' => $file,
                'url' =>  url('storage/temp/' . $process_id . '/' . $file),
                'size' => File::size("{$processedDir}{$file}"),
            ];

            return $results;
        });

        return compact('process_id', 'files', 'success');
    }

    public function ImageToPDF($request)
    {
        $success = true;
        $files = $request->file('files');
        $file_password = $request->input('password', null);

        $merge = $request->input('merge_pages', null);
        $margin = $request->input('margin', 'no-margin');
        $orientation = $request->input('page_orientation', null);
        $pageSize = $request->input('page_size', null);

        $process_id = (string) Str::uuid();
        $pagesData = collect(json_decode($request->input('fileData'), true));

        $files = Cache::remember($process_id, job_cache_time(), function () use ($files, $merge, $margin, $orientation, $pageSize, $pagesData, $process_id, $file_password) {
            Storage::disk('public')->makeDirectory("temp/{$process_id}");
            Storage::disk('local')->makeDirectory("temp/{$process_id}");
            $ilovepdf = new LovePdfWrapper($this->tool->settings->love_pdf_public_id, $this->tool->settings->love_pdf_secret_key);
            $outputDir = storage_path("app/temp/{$process_id}/");
            $processedDir = storage_path("app/public/temp/{$process_id}/");
            $margin = $margin == 'small-margin' ? 20 : ($margin == 'big-margin' ? 40 : 0);
            $merge = (bool) $merge;
            $pageSize = $pageSize != 'A4' ? strtolower($pageSize) : 'A4';
            $orientation = $orientation != 'auto' ? strtolower($orientation) : false;

            $myTask = $ilovepdf->newTask($this->task);
            $myTask->setPagesize($pageSize);
            if ($orientation) {
                $myTask->setOrientation($orientation);
            }
            $myTask->setMargin($margin);
            $myTask->setMergeAfter($merge);

            $filesData = $pagesData->groupBy('index');
            foreach ($filesData as $index => $data) {
                $file = $files[$index];
                $pageData = $pagesData->where('index', $index)->first();
                $rotation = $pageData['rotation'] ?? null;

                $uploadedFile = tempFileUpload($file, false, false, $process_id);
                $uploadedPath = Storage::disk($uploadedFile['disk'])->path($uploadedFile['path']);
                $taskFile = $myTask->addFile($uploadedPath);
                if ($rotation > 0) {
                    $taskFile->setRotation($rotation);
                }
            }
            if ($merge) {
                $myTask->setOutputFilename("{$this->tool->name}");
            }
            $myTask->execute();
            $myTask->download(!$merge ? $outputDir : $processedDir);

            if (!$merge) {
                $is_valid = Zip::check("{$outputDir}output.zip");
                if (!$is_valid) {
                    throw new Exception(__('common.somethingWentWrong'));
                }

                $zip = Zip::open("{$outputDir}output.zip");
                $zip->extract("{$processedDir}");
                $results = [];
                foreach ($zip->listFiles() as $index => $file) {
                    $results[] = [
                        'filename' => $file,
                        'url' =>  url('storage/temp/' . $process_id . '/' . $file),
                        'size' => File::size("{$processedDir}{$file}"),
                    ];
                }
            } else {
                $file = "{$this->tool->name}.pdf";
                $results[] = [
                    'filename' => $file,
                    'url' =>  url('storage/temp/' . $process_id . '/' . $file),
                    'size' => File::size("{$processedDir}{$file}"),
                ];
            }

            return $results;
        });

        return compact('process_id', 'files', 'success');
    }

    public function urlToPDF($request)
    {
        $success = true;
        if (!isset($this->tool->settings->love_pdf_public_id)) {
            $message = __('common.apiKeyNotProvided');
            $success = false;

            return compact('message', 'success');
        }

        $process_id = (string) Str::uuid();
        $url = $request->input('url');
        $view_width = $request->input('view_width', 1920);
        $page_size = $request->input('page_size', 'A4');
        $page_orientation = $request->input('page_orientation', 'potrait');
        $page_margin = $request->input('page_margin', 0);
        $single_page = (bool) $request->input('single_page', false);

        $files = Cache::remember($process_id, job_cache_time(), function () use ($url, $view_width, $page_size, $page_orientation, $page_margin, $single_page, $process_id) {
            Storage::disk('public')->makeDirectory("temp/{$process_id}");
            Storage::disk('local')->makeDirectory("temp/{$process_id}");
            $ilovepdf = new LovePdfWrapper($this->tool->settings->love_pdf_public_id, $this->tool->settings->love_pdf_secret_key);
            $processedDir = storage_path("app/public/temp/{$process_id}/");

            $myTask = $ilovepdf->newTask('htmlpdf');
            $myTask->addUrl($url);
            $myTask->setViewWidth($view_width);
            $myTask->setPageSize($page_size);
            $myTask->setPageOrientation($page_orientation);
            $myTask->setPageMargin($page_margin);
            $myTask->setSinglePage($single_page);
            $myTask->setOutputFilename("{$this->tool->name}");
            $myTask->execute();
            $myTask->download($processedDir);

            $file = "{$this->tool->name}.pdf";
            $results[] = [
                'filename' => $file,
                'url' =>  url('storage/temp/' . $process_id . '/' . $file),
                'size' => File::size("{$processedDir}{$file}"),
            ];

            return $results;
        });

        return compact('process_id', 'files', 'success');
    }

    public function unlock($request)
    {
        $files = $request->file('pdf');
        $pagesData = collect(json_decode($request->input('fileData'), true));
        $success = true;
        if (!isset($this->tool->settings->love_pdf_public_id)) {
            $message = __('common.apiKeyNotProvided');
            $success = false;
            return compact('message', 'success');
        }

        $process_id = (string) Str::uuid();
        $files = Cache::remember($process_id, job_cache_time(), function () use ($files, $process_id, $pagesData) {
            Storage::disk('public')->makeDirectory("temp/{$process_id}");
            Storage::disk('local')->makeDirectory("temp/{$process_id}");
            $ilovepdf = new LovePdfWrapper($this->tool->settings->love_pdf_public_id, $this->tool->settings->love_pdf_secret_key);
            $outputDir = storage_path("app/temp/{$process_id}/");
            $processedDir = storage_path("app/public/temp/{$process_id}/");

            $myTask = $ilovepdf->newTask($this->task);
            $filesData = $pagesData->groupBy('index');
            foreach ($filesData as $index => $data) {
                $file = $files[$index];
                $pageData = $pagesData->where('index', $index);
                $uploadedFile = tempFileUpload($file, false, false, $process_id);
                $uploadedPath = Storage::disk($uploadedFile['disk'])->path($uploadedFile['path']);
                $processed_file = $myTask->addFile($uploadedPath);
                $processed_file->setPassword($pageData->first()['password'] ?? '');
            }
            $myTask->setOutputFilename("{$this->tool->name}");
            $myTask->execute();
            $myTask->download(count($files) > 1 ? $outputDir : $processedDir);
            if (count($files) > 1) {
                $is_valid = Zip::check("{$outputDir}output.zip");
                if (!$is_valid) {
                    throw new Exception(__('common.somethingWentWrong'));
                }

                $zip = Zip::open("{$outputDir}output.zip");
                $zip->extract("{$processedDir}");
                $results = [];
                foreach ($zip->listFiles() as $index => $file) {
                    $results[] = [
                        'filename' => $file,
                        'url' =>  url('storage/temp/' . $process_id . '/' . $file),
                        'size' => File::size("{$processedDir}{$file}"),
                    ];
                }
            } else {
                $file = "{$this->tool->name}.pdf";
                $results[] = [
                    'filename' => $file,
                    'url' =>  url('storage/temp/' . $process_id . '/' . $file),
                    'size' => File::size("{$processedDir}{$file}"),
                ];
            }

            return $results;
        });

        return $files[0] ?? null;
        return compact('process_id', 'files', 'success');
    }

    public function parseIndividually($request)
    {
        return $this->parse($request);
    }

    public function SplitTask($request)
    {
        $files = $request->file('files');

        $file_password = $request->input('password', null);
        $process_id = (string) Str::uuid();
        $pagesData = collect(json_decode($request->input('fileData'), true));
        $page_range = $this->getPageRange($pagesData->pluck('page'));

        $files = Cache::remember(
            $process_id,
            job_cache_time(),
            function () use ($files, $pagesData, $process_id, $file_password, $page_range) {
                Storage::disk('public')->makeDirectory("temp/{$process_id}");
                Storage::disk('local')->makeDirectory("temp/{$process_id}");
                $ilovepdf = new LovePdfWrapper($this->tool->settings->love_pdf_public_id, $this->tool->settings->love_pdf_secret_key);
                $outputDir = storage_path("app/temp/{$process_id}/");
                $processedDir = storage_path("app/public/temp/{$process_id}/");

                $myTask = $ilovepdf->newTask($this->task);
                $filesData = $pagesData->groupBy('index');
                foreach ($filesData as $index => $data) {
                    $file = $files[$index];
                    $pageData = $pagesData->where('index', $index);
                    $uploadedFile = tempFileUpload($file, false, false, $process_id);
                    $uploadedPath = Storage::disk($uploadedFile['disk'])->path($uploadedFile['path']);
                    $myTask->addFile($uploadedPath);
                    $myTask->setRanges($page_range);
                    $myTask->setMergeAfter(true);
                    if (!empty($file_password)) {
                        $myTask->setPassword($file_password);
                    }
                }

                $myTask->setOutputFilename("{$this->tool->name}");
                $myTask->execute();
                $myTask->download(count($files) > 1 ? $outputDir : $processedDir);
                if (count($files) > 1) {
                    $is_valid = Zip::check("{$outputDir}output.zip");
                    if (!$is_valid) {
                        throw new Exception(__('common.somethingWentWrong'));
                    }

                    $zip = Zip::open("{$outputDir}output.zip");
                    $zip->extract("{$processedDir}");
                    $results = [];
                    foreach ($zip->listFiles() as $index => $file) {
                        $results[] = [
                            'filename' => $file,
                            'url' =>  url('storage/temp/' . $process_id . '/' . $file),
                            'size' => File::size("{$processedDir}{$file}"),
                        ];
                    }
                } else {
                    $file = "{$this->tool->name}.pdf";
                    $results[] = [
                        'filename' => $file,
                        'url' =>  url('storage/temp/' . $process_id . '/' . $file),
                        'size' => File::size("{$processedDir}{$file}"),
                    ];
                }

                return $results;
            }
        );
        return $files[0] ?? null;
        return compact('process_id', 'files', 'success');
    }


    /**
     * Get Page Range
     *
     * Converts the page numbers array into a qpdf-compatible page range string.
     *
     * @param array $pageNumbers - The page numbers array.
     * @return string - The page range string.
     */
    private function getPageRange($pageNumbers)
    {
        $ranges = [];
        $start = $pageNumbers[0];
        $end = $pageNumbers[0];

        for ($i = 1; $i < count($pageNumbers); $i++) {
            if ($pageNumbers[$i] == $end + 1) {
                $end = $pageNumbers[$i];
            } else {
                $ranges[] = $this->formatPageRange($start, $end);
                $start = $pageNumbers[$i];
                $end = $pageNumbers[$i];
            }
        }

        $ranges[] = $this->formatPageRange($start, $end);

        return implode(",", $ranges);
    }

    /**
     * Format Page Range
     *
     * Formats the start and end page numbers into a page range string.
     *
     * @param int $start - The start page number.
     * @param int $end - The end page number.
     * @return string - The formatted page range string.
     */
    private function formatPageRange($start, $end)
    {
        if ($start == $end) {
            return $start == 'all' ? '' : "$start";
        } else {
            return "$start-$end";
        }
    }
}
