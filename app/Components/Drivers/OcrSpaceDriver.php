<?php

namespace App\Components\Drivers;

use Exception;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use App\Contracts\ToolDriverInterface;

class OcrSpaceDriver implements ToolDriverInterface
{
    protected $endpoint = "https://api.ocr.space/parse/image";

    protected $endpointFromUrl = "https://api.ocr.space/parse/imageurl";

    protected $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function parse($file)
    {
        if (empty($this->apiKey)) {
            throw new Exception(__('admin.OcrSpaceApiKeyMissing'));
        }

        $process_id = (string) Str::uuid();
        list($text, $filename) = Cache::remember($process_id, job_cache_time(), function () use ($file) {
            $client = new Client();
            $r = $client->request(
                'POST',
                $this->endpoint,
                [
                    'headers' => ['apiKey' => $this->apiKey],
                    'multipart' => [
                        [
                            'name'          => 'file',
                            'contents'      => $file->get(),
                            'filename'      => $file->getClientOriginalName(),
                        ]
                    ],
                ],
                ['file' => $file->get()]
            );

            $response = json_decode($r->getBody(), true);

            if ($response['IsErroredOnProcessing']) {
                // return [false, $response['ErrorMessage'][0], null];
                return $response['ErrorMessage'][0];
            }

            $text = '';
            foreach ($response['ParsedResults'] as $pareValue) {
                $text .= $pareValue['ParsedText'];
            }

            return [$text, pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '-' . time()];
        });

        return compact('text', 'filename', 'process_id');
    }
}
