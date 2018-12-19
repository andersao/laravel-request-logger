<?php namespace Prettus\RequestLogger\Handler;

use Monolog\Handler\HandlerInterface;
use Monolog\Handler\SyslogHandler;
use Monolog\Logger;

class SysloggerHandler extends SyslogHandler
{
    public function __construct($filename = null)
    {
        $filename = !is_null($filename) ? $filename : config("request-logger.logger.syslog" );
        parent::__construct($filename);
    }
}
