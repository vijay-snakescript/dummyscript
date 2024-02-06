<?php

namespace App\Tools;

use App\Models\Tool;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use ZanySoft\Zip\Facades\Zip;
use App\Contracts\ToolInterface;
use App\Helpers\Classes\PDFManager;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class WatermarkPdf implements ToolInterface
{
    public function render(Request $request, Tool $tool)
    {
        return view('tools.watermark-pdf', compact('tool'));
    }

    public function handle(Request $request, Tool $tool)
    {
        $validator = Validator::make($request->all(), [
            'files' => "required|array|max:{$tool->no_file_tool}",
            'files.*' => "required|mimes:pdf|max:" . convert_mb_into_kb($tool->fs_tool),
            'watermark' => 'required|in:text,image',
            'text' => 'required_if:watermark,text',
            'text-position' => 'required_if:watermark,text',
            'text-overlay' => 'required_if:watermark,text',
            'font-family' => 'required_if:watermark,text',
            'image-overlay' => 'required_if:watermark,image',
            'image-position' => 'required_if:watermark,image',
            'image-transparency' => 'required_if:watermark,image',
            'image' => 'required_if:watermark,image|mimes:png,jpg,jpeg',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $process_id = Str::uuid()->toString();
        $results = ['process_id' => $process_id];
        $results['files'] = Cache::remember($process_id, job_cache_time(), function () use ($request, $process_id) {
            $files = $request->file('files');
            $watermarkImage = $request->file('image');
            $watermark = $request->input('watermark');
            $text = $request->input('text');
            $textSize = $request->input('text-size');
            $fontFamily = $request->input('font-family');
            $transparency = $watermark == 'text' ? $request->input('text-transparency') : $request->input('image-transparency');
            $transparency = $transparency > 0 ? $transparency / 100 : 0;
            $rotation = $watermark == 'text' ? $request->input('text-rotation') : $request->input('image-rotation');
            $overlay = $watermark == 'text' ? $request->input('text-overlay') : $request->input('image-overlay');
            $overlay = $overlay == 1 ? true : false;
            $position = $watermark == 'text' ? $request->input('text-position') : $request->input('image-position');
            $color = $request->input('watermark-color');
            $color = $this->convertColor($color);
            if ($watermark == 'image') {
                $watermarkUploaded = tempFileUpload($watermarkImage, false, false, $process_id);
                $watermarkPath = Storage::disk($watermarkUploaded['disk'])->path($watermarkUploaded['path']);
            }
            try {
                foreach ($files as $index => $file) {
                    $upload = tempFileUpload($file, false, false, $process_id);
                    $path = Storage::disk($upload['disk'])->path($upload['path']);

                    $filename = $upload['filename'];
                    $output = storage_path("app/public/temp/{$process_id}/{$filename}");
                    Storage::disk('public')->makeDirectory("temp/{$process_id}");

                    $process = new PDFManager();
                    if ($watermark == 'text') {
                        $process->setWatermarkFontFamily($fontFamily);
                        $process->setWatermarkText($text);
                        $process->setWatermarkColor(...$color);
                        $process->setWatermarkFontSize($textSize);
                    } else {
                        $process->setWatermarkImage($watermarkPath);
                    }
                    $process->setWatermarkAlpha($transparency);
                    $process->setWatermarkRotation($rotation);
                    $process->setWatermarkOnTop($overlay);
                    $process->setWatermarkPosition($position);
                    $process->importPdf($path);
                    $process->Output($output, 'F');
                }
            } catch (\Exception $e) {
            }

            return $this->output($process_id);
        });

        return view('tools.watermark-pdf', compact('tool', 'results'));
    }

    protected function convertColor($color = '#000000')
    {
        if (preg_match('/^#?(([a-f0-9]{3}){1,2})$/i', $color)) {
            $color = hex2rgba($color);
        }

        preg_match('/^rgba?[\s+]?\([\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?/i', $color, $rgb);

        return ['r' => $rgb[1] ?? 0, 'g' => $rgb[2] ?? 0, 'b' => $rgb[3] ?? 0];
    }

    public function postAction(Request $request, $tool)
    {
        $process_id = $request->process_id;
        if (!$process_id) {
            abort(404);
        }

        $storeDisk = Storage::disk(config('artisan.temporary_files_disk', 'local'));
        $storePath = config('artisan.temporary_files_path', 'temp') . DIRECTORY_SEPARATOR . 'zip' . DIRECTORY_SEPARATOR . $process_id;
        $storeDisk->makeDirectory($storePath);
        $zip = Zip::create($storeDisk->path("{$storePath}/{$tool->slug}.zip"));
        $zip->add(storage_path('app/public/temp/' . $process_id), true);
        $zip->close();

        return $storeDisk->download("{$storePath}/{$tool->slug}.zip");
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
}
