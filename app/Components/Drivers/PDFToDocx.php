<?php

namespace App\Components\Drivers;

use App\Models\Tool;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Exception\ProcessFailedException;

class PDFToDocx
{
    protected $files = [];
    protected $pdf2docxPath;
    protected $tool;
    protected $outputDirectory;

    public function __construct(Tool $tool)
    {
        $this->tool = $tool;
        $this->pdf2docxPath = !empty($this->tool->settings->docx_bin_path) ? escapeshellarg($this->tool->settings->docx_bin_path) : "pdf2docx";
    }

    public function parse($request)
    {
        $success = true;
        $files = $request->file('files');
        $format = $request->input('format', 'docx');
        $pagesData = collect(json_decode($request->input('fileData'), true));

        $process_id = (string) Str::uuid();
        $files = Cache::remember($process_id, job_cache_time(), function () use ($files, $pagesData, $format, $process_id) {
            Storage::disk('public')->makeDirectory("temp/{$process_id}");

            foreach ($files as $index => $file) {
                $filename = filenameWithoutExtension($file->getClientOriginalName());
                $output = storage_path("app/public/temp/{$process_id}/{$filename}.{$format}");
                $pageData = $pagesData->where('index', $index)->first();
                $password = $pageData['password'] ?? '';
                $file_url = tempFileUpload($file, false, false, $process_id);

                $this->addFile(storage_path('app/' . $file_url['path']), $password);
                $this->setOutputDirectory($output);
                $this->convert();
                // $script = $this->generateScript($process_id, $filename, storage_path('app/' . $file_url['path']), $output);
                // $this->runScript($script);
            }

            return $this->output($process_id);
        });

        return compact('process_id', 'files', 'success');
    }

    /**
     * Read the outputDir and return files
     *
     * @return Array|bool
     */
    protected function output($process_id)
    {
        $path = Storage::disk('public')->path("temp/{$process_id}");
        $files = File::allFiles($path);

        $resultFiles = collect();
        foreach ($files as $file) {
            $resultFiles->push([
                'filename' => $file->getFilename(),
                'url' => url(Storage::disk('public')->url("temp/{$process_id}/{$file->getFilename()}")),
                'size' => $file->getSize(),
            ]);
        }

        return $resultFiles->count() == 0 ? false : $resultFiles->toArray();
    }

    protected function addFile(string $filePath, ?string $password = null)
    {
        $this->files = [[
            'path' => $filePath,
            'password' => $password,
        ]];
    }

    protected function buildCommand(): string
    {
        if (empty($this->files)) {
            throw new \Exception('No files to convert.');
        }

        if (empty($this->outputDirectory)) {
            throw new \Exception('Output directory not set.');
        }

        $commands = [$this->pdf2docxPath, 'convert'];
        foreach ($this->files as $file) {
            $fileCommand = $file['path'];
            if (!empty($file['password'])) {
                $fileCommand .= ' -pw ' . escapeshellarg($file['password']);
            }

            $commands[] = $fileCommand;
        }
        $commands[] = $this->outputDirectory;

        $command = implode(' ', $commands);

        return $command;
    }

    protected function generateScript($process_id, $filename, $file, $output)
    {
        $file = Str::replace('\\', "/", $file);
        $output = Str::replace('\\', "/", $output);
        $script = "from pdf2docx import Converter

pdf_file = \"{$file}\"
docx_file = \"{$output}\"

# convert pdf to docx
cv = Converter(pdf_file)
cv.convert(docx_file)      # all pages by default
cv.close()";

        Storage::disk('local')->put("temp/{$process_id}/{$filename}.py", $script);

        return Storage::disk('local')->path("temp/{$process_id}/{$filename}.py");
    }

    protected function runScript($file)
    {
        try {
            $process = Process::fromShellCommandline("python " . escapeshellarg($file) . " 2>&1");
            $process->mustRun();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            return $process->getOutput();
        } catch (ProcessFailedException $e) {
            info($e->getMessage());
            throw new \Exception(__('common.somethingWentWrong'));
        }
    }

    protected function setOutputDirectory(string $outputDirectory)
    {
        $this->outputDirectory = $outputDirectory;
    }

    protected function convert()
    {
        $command = $this->buildCommand();

        try {
            $process = Process::fromShellCommandline($command);
            $process->mustRun();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            return $process->getOutput();
        } catch (ProcessFailedException $e) {
            info($e->getMessage());
            throw new \Exception(__('common.somethingWentWrong'));
        }
    }
}
