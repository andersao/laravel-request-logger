<?php namespace Prettus\RequestLogger\Handler;

use Monolog\Handler\HandlerInterface;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

/**
 * Class HttpLoggerHandler
 * @package Prettus\RequestLogger\Handler
 */
class HttpLoggerHandler extends RotatingFileHandler implements HandlerInterface {

    public function __construct($filename = null, $maxFiles = 0, $level = Logger::DEBUG, $bubble = true, $filePermission = null, $useLocking = false)
    {

        $filename = !is_null($filename) ? $filename : config("request-logger.logger.file", storage_path("logs/http.log") );
        parent::__construct($filename, $maxFiles, $level, $bubble, $filePermission, $useLocking);
    }

}