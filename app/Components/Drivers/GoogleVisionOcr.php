<?php

namespace App\Components\Drivers;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use App\Contracts\ToolDriverInterface;
use Google\Cloud\Vision\V1\ImageAnnotatorClient;

class GoogleVisionOcr implements ToolDriverInterface
{
    private $credentials;
    public function __construct()
    {
        $this->credentials = storage_path('app/google-vision.json');
    }

    public function parse($file)
    {
        if (!file_exists($this->credentials)) {
            throw new Exception(__('admin.GoogleVisionApiKeyMissing'));
        }

        $process_id = (string) Str::uuid();
        list($text, $filename) = Cache::remember($process_id, job_cache_time(), function () use ($file) {
            $client = new ImageAnnotatorClient([
                'credentials' => $this->credentials
            ]);

            // Annotate an image, detecting faces.
            $imageOcr = $client->textDetection($file->get());

            $annotation = $imageOcr->getTextAnnotations()[0];
            $text = '';
            if ($annotation) {
                $text = $annotation->getDescription();
            }

            return [$text, pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '-' . time()];
        });

        return compact('text', 'filename', 'process_id');
    }
}
