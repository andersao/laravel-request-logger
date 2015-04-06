<?php
/*
|--------------------------------------------------------------------------
| Prettus Request Logger Config
|--------------------------------------------------------------------------
|
|
*/
return [

    /*
    |--------------------------------------------------------------------------
    | Logger
    |--------------------------------------------------------------------------
    |
    | - enabled : true or false
    | - handlers: Array of the Monolog\Handler\HandlerInterface
    | - file : File name for the Http Logger
    | - level: [notice, info, debug, emergency, alert, critical, error, warning]
    | - format : Format for logger output
    */
    'logger' => [
        'enabled'   => true,
        'handlers'  => ['Prettus\RequestLogger\Handler\HttpLoggerHandler'],
        'file'      => storage_path("logs/http.log"),
        'level'     => 'info',
        'format'    => 'common'
    ]
];