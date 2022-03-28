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
    | Per page
    |--------------------------------------------------------------------------
    |
    | If you use DB how many item per page do you want
    |
    */

    'per_page' => 25,



    /*
    |--------------------------------------------------------------------------
    | Filename Format
    |--------------------------------------------------------------------------
    |
    | The name and format of the log files which are created.
    | Only applicable if the 'file' driver is being used
    | [uuid] is a unique id for the request, if not present in filename it will
    | append before the extension
    |
    */

    'filename' => env('API_LOGS_FILENAME_FORMAT', 'api-{Y-m-d}-[uuid].log'),

   /*
   |--------------------------------------------------------------------------
   | Routes group config
   |--------------------------------------------------------------------------
   |
   | The default group settings for the routes.
   |
   */
    'route'          => [
        'prefix'     => 'apilogs',
        'middleware' => null,
    ],

    /*
    |--------------------------------------------------------------------------
    |  Log raw payload
    |--------------------------------------------------------------------------
    |
    | This will log the raw request from php://input so you will get all details
    | BUT this will also log the exclusions, since we are not able to find field
    | in the php://input, by default we use request()->all() encoded in JSON
    |
    */
    'payload_raw' => false,

    /*
    |--------------------------------------------------------------------------
    |  Log response
    |--------------------------------------------------------------------------
    |
    | This will log the result send to the client
    |
    */
    'response' => true,

    /*
    |--------------------------------------------------------------------------
    |  Try to autodetect json, xml, html and make a pretty display
    |--------------------------------------------------------------------------
    |
    | If this is false plain response will be show
    |
    */
    'response_autodetect' => true,


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
    ],


    /*
    |--------------------------------------------------------------------------
    |  Headers Exclusions
    |--------------------------------------------------------------------------
    |
    | This sets which headers data is excluded from the logging, case non-sensitive
    |
    */
    'dont_log_headers' => [
        'Authorization'
    ]
];
