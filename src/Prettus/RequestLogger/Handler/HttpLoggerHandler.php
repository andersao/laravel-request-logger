<?php namespace Prettus\RequestLogger\Handler;

use Monolog\Handler\HandlerInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

/**
 * Class HttpLoggerHandler
 * @package Prettus\RequestLogger\Handler
 */
class HttpLoggerHandler extends StreamHandler implements HandlerInterface {

    public function __construct($stream = null, $level = Logger::DEBUG, $bubble = true, $filePermission = null, $useLocking = false)
    {
        $stream = !is_null($stream) ? $stream : storage_path("logs/http.log");
        parent::__construct($stream, $level, $bubble, $filePermission, $useLocking);
    }

}