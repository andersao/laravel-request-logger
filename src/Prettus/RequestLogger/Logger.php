<?php

namespace Prettus\RequestLogger;

use Psr\Log\LoggerInterface as Log;

/**
 * Class Logger
 * @package Prettus\Logger\Request
 * @author Anderson Andrade <contato@andersonandra.de>
 */
class Logger implements Log
{

    /**
     * @var \Monolog\Logger;
     */
    protected $monolog;

    /**
     *
     */
    public function __construct()
    {
        $this->monolog = \Illuminate\Support\Facades\Log::getLogger();

        if( config('request-logger.logger.enabled') && $handlers = config('request-logger.logger.handlers') ) {
            if( count($handlers) ) {
                //Remove default laravel handler
                $this->monolog->popHandler();

                foreach($handlers as $handler) {
                    if( class_exists($handler) ) {
                        $this->monolog->pushHandler(app($handler));
                    } else {
                        throw new \Exception("Handler class [{$handler}] does not exist");
                    }
                }
            }
        }
    }

     /**
     * System is unusable.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function emergency($message, array $context = array())
    {
        $this->monolog->emergency($message, $context);
    }

    /**
     * Log an alert message to the logs.
     *
     * @param  string $message
     * @param  array $context
     * @return void
     */
    public function alert($message, array $context = array())
    {
        $this->monolog->alert($message, $context);
    }

    /**
     * Log a critical message to the logs.
     *
     * @param  string $message
     * @param  array $context
     * @return void
     */
    public function critical($message, array $context = array())
    {
        $this->monolog->critical($message, $context);
    }

    /**
     * Log an error message to the logs.
     *
     * @param  string $message
     * @param  array $context
     * @return void
     */
    public function error($message, array $context = array())
    {
        $this->monolog->error($message, $context);
    }

    /**
     * Log a warning message to the logs.
     *
     * @param  string $message
     * @param  array $context
     * @return void
     */
    public function warning($message, array $context = array())
    {
        $this->monolog->warning($message, $context);
    }

    /**
     * Log a notice to the logs.
     *
     * @param  string $message
     * @param  array $context
     * @return void
     */
    public function notice($message, array $context = array())
    {
        $this->monolog->notice($message, $context);
    }

    /**
     * Log an informational message to the logs.
     *
     * @param  string $message
     * @param  array $context
     * @return void
     */
    public function info($message, array $context = array())
    {
        $this->monolog->info($message, $context);
    }

    /**
     * Log a debug message to the logs.
     *
     * @param  string $message
     * @param  array $context
     * @return void
     */
    public function debug($message, array $context = array())
    {
        $this->monolog->debug($message, $context);
    }

    /**
     * Log a message to the logs.
     *
     * @param  string $level
     * @param  string $message
     * @param  array $context
     * @return void
     */
    public function log($level, $message, array $context = array())
    {
        $this->monolog->log($level, $message, $context);
    }

    /**
     * Register a file log handler.
     *
     * @param  string $path
     * @param  string $level
     * @return void
     */
    public function useFiles($path, $level = 'debug')
    {

    }

    /**
     * Register a daily file log handler.
     *
     * @param  string $path
     * @param  int $days
     * @param  string $level
     * @return void
     */
    public function useDailyFiles($path, $days = 0, $level = 'debug')
    {

    }
}
