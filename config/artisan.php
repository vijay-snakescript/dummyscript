
<?php

return [
    'installed' => env('APP_INSTALLED'),
    'version' => env('APP_VERSION', '1.0.0'),
    'admin_prefix' => env('APP_ADMIN_PREFIX', 'admin'),
    'super_admin_role' => env('SUPER_ADMIN_ROLE', 1),
    'front_theme' => env('FRONT_THEME', 'canvas'),

    /*
    |--------------------------------------------------------------------------
    | Temporary Path
    |--------------------------------------------------------------------------
    |
    | When initially uploading the files we store them in this path
    | By default, it is stored on the public disk which defaults to `/storage/app/public/{temporary_files_path}`
    |
    */
    'temporary_files_path' => env('UPLOADER_TEMP_PATH', 'temp'),
    'temporary_files_disk' => env('UPLOADER_TEMP_DISK', 'local'),

    'public_files_path' => env('PUBLIC_FILES_PATH', 'uploads'),
    'public_files_disk' => env('PUBLIC_FILES_DISK', 'public'),

    /*
    |--------------------------------------------------------------------------
    | Chunks path
    |--------------------------------------------------------------------------
    |
    | When using chunks, we want to place them inside of this folder.
    | Make sure it is writeable.
    | Chunks use the same disk as the temporary files do.
    |
    */
    'chunks_path' => env('UPLOADER_CHUNKS_PATH', 'uploads' . DIRECTORY_SEPARATOR . 'chunks'),
    'input_name' => env('UPLOAD_INPUT_NAME', 'upload'),
    'default_gateway' => env('DEFAULT_PAYMENT_GATEWAY'),
    'gateway_defaults' => [
        'testMode' => env('OMNIPAY_TESTMODE', false),
    ],

    'seo' => [
        'page_title_min' => 20,
        'page_title_max' => 60,
        'meta_description_min' =>  80,
        'meta_description_max' =>  160,
        'dom_size' =>  1500,
        'link_count' =>  150,
        'load_time' =>  2.5,
        'page_size' =>  100000,
        'http_requests_limit' => 25,
        'content_length' => 1000,
    ],

    /**
     * Use this setting to enable the cookie consent dialog.
     */
    'enabled_cookie_consent' => env('COOKIE_CONSENT_ENABLED', true),

    /**
     * The name of the cookie in which we store if the user
     * has agreed to accept the conditions.
     */
    'cookie_name' => '_cookie_consent',

    /**
     * Set the cookie duration in days.  Default is 365 * 20.
     */
    'cookie_lifetime' => 365 * 20,

    /**
     * Do not change or remove this like.
     */
    'encrypt_key' => '3SZDb9gGYJ6qJis8Ky4w4mlwCPUSr1Tj',
];
