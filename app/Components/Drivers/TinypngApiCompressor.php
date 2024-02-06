<?php

namespace App\Components\Drivers;

use GuzzleHttp\Client;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use App\Contracts\ToolDriverInterface;
use Illuminate\Support\Facades\Storage;


class TinypngApiCompressor implements ToolDriverInterface
{
    protected $apikey = null;

    protected $endpoint = "https://api.tinify.com/shrink";

    public function __construct($apikey)
    {
        $this->apikey = $apikey;
    }

    public function parse($request)
    {
        $process_id = $request->input('process_id');
        $filename = $request->input('file');
        $message = null;
        // Fetch Job
        $job = Cache::get($process_id);
        if (!$job) {
            $message = response()->json(['status' => false, 'message' => __('tools.theRequestExpired')]);
        }

        // Find image in job
        $file = collect($job)->firstWhere('original_filename', $filename);
        if (!$file) {
            $message = response()->json(['status' => false, 'message' => __('tools.theRequestExpired')]);
        }

        if (!empty($message)) {
            return ['status' => false, 'response' => $message];
        }

        // File found send it to tinypng for compression.
        $image = Storage::disk($file['disk'])->get($file['path']);
        $client = new Client();
        try {
            $request = $client->request(
                'POST',
                $this->endpoint,
                [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'Accept'       => 'application/json',
                    ],
                    'curl' => [
                        CURLOPT_USERPWD  => "api:{$this->apikey}"
                    ],
                    'body' => $image,
                ],
            );
            $response = json_decode($request->getBody()->getContents(), true);
        } catch (\Exception $e) {
            return $e;
        }
        $output = $response['output']['url'];
        $image = file_get_contents($output);
        $resource = UploadedFile::fake()->createWithContent($file['original_filename'], $image);
        $result = tempFileUpload($resource, true, false, $process_id);

        Cache::put($process_id . "-download-all", $result, job_cache_time());
        $result['original_size'] = $file['size'];

        return [
            'success' => true,
            'filename' => $result['original_filename'],
            'size' => $result['size'],
            'original_size' => $file['size'],
            'compression_ratio' => round((100 - ($result['size'] / $file['size'] * 100)), 2),
            'url' => url($result['url']),
        ];
    }
}
