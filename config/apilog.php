<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Log Storage Driver
    |--------------------------------------------------------------------------
    |
    | This determines how the logs are stored.
    | Currently supported drivers are 'db' and 'file'
    | If you want to use your own custom driver, you can parse through your
    | Class name here: Eg: `\App\Apilogs\CustomLogger::class`
    | Note: your log driver MUST implement ApiLoggerInterface!
    |
    */

    'driver' => env('API_LOGS_DRIVER', 'file'),


    /*
    |--------------------------------------------------------------------------
    | Filename Format
    |--------------------------------------------------------------------------
    |
    | The name and format of the log files which are created.
    | Only applicable if the 'file' driver is being used
    |
    */

    'filename' => env('API_LOGS_FILENAME_FORMAT', 'api-{Y-m-d}.log'),


    /*
    |--------------------------------------------------------------------------
    |  Request Exclusions
    |--------------------------------------------------------------------------
    |
    | This sets which request data is excluded from the logging
    |
    */
    'dont_log' => [
        'password', 'password_confirmation', 'new_password', 'old_password',
    ]
];
