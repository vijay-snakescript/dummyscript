<?php

namespace App\Providers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;

class WasabiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Storage::extend('wasabi', function ($app, $config) {
            $conf = [
                'endpoint' => "https://" . $config['bucket'] . ".s3." . $config['region'] . ".wasabisys.com/",
                'bucket_endpoint' => true,
                'credentials' => [
                    'key' => $config['key'],
                    'secret' => $config['secret'],
                ],
                'region' => $config['region'],
                'version' => 'latest',
            ];

            $client = new S3Client($conf);

            $adapter = new AwsS3Adapter($client, $config['bucket'], $config['root']);

            $filesystem = new Filesystem($adapter);

            return $filesystem;
        });
    }
}
