<?php namespace Prettus\RequestLogger\Filters;

use Prettus\RequestLogger\Helpers\RequestInterpolation;
use Prettus\RequestLogger\Logger;

/**
 * Class Logger
 * @package Prettus\Logger\Request
 */
class RequestLogger extends Logger
{
    /**
     * @var RequestInterpolation
     */
    protected $requestInterpolation;

    /**
     *
     */
    const LOG_CONTEXT = "REQUEST";

    public function __construct(RequestInterpolation $requestInterpolation){
        $this->requestInterpolation = $requestInterpolation;
        parent::__construct();
    }

    /**
     *
     */
    public function filter()
    {

        if( config('request-logger.request.enabled') )
        {
            $message = config('request-logger.request.format', "{ip} {remote_user} {date} {method} {url} {referrer} {user_agent}");
            $message = $this->requestInterpolation->interpolate($message);
            $this->log( config('request-logger.logger.level', 'info') , $message, [self::LOG_CONTEXT ]);
        }
    }

}