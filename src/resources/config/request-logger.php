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
    | - handlers: Array of the Monolog\Handler\HandlerInterface
    | - level: [notice, info, debug, emergency, alert, critical, error, warning]
    |
    */
    'logger' => [
        'handlers'  => [],
        'level'     => 'info'
    ],

    /*
    |--------------------------------------------------------------------------
    | Request
    |--------------------------------------------------------------------------
    |
    */
    'request' => [
        'enabled' => true,
        'format'  => '{ip} {remote_user} {date} {method} {url} {referrer} {user_agent}'
    ],

    /*
    |--------------------------------------------------------------------------
    | Response
    |--------------------------------------------------------------------------
    |
    */
    'response' => [
        'enabled' => true,
        'format'  => '{ip} {remote_user} {date} {method} {url} HTTP/{http_version} {status} {content_length} {referrer} {user_agent}'
    ]
];