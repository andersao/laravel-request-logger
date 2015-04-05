<?php namespace Prettus\RequestLogger\Filters;

use Prettus\RequestLogger\Helpers\RequestInterpolation;
use Prettus\RequestLogger\Helpers\ResponseInterpolation;
use Prettus\RequestLogger\Logger;

/**
 * Class Logger
 * @package Prettus\Logger\Request
 */
class ResponseLogger extends Logger
{
    /**
     * @var RequestInterpolation
     */
    protected $requestInterpolation;

    /**
     * @var ResponseInterpolation
     */
    protected $responseInterpolation;

    public function __construct(RequestInterpolation $requestInterpolation, ResponseInterpolation $responseInterpolation){
        $this->requestInterpolation = $requestInterpolation;
        $this->responseInterpolation = $responseInterpolation;
    }

    /**
     *
     */
    const LOG_CONTEXT = "RESPONSE";

    /**
     *
     */
    public function filter()
    {
        if( config('request-logger.response.enabled') )
        {
            $message = config('request-logger.response.format', "{ip} {remote_user} {date} {method} {url} HTTP/{http_version} {status} {content_length} {referrer} {user_agent}");
            $message = $this->responseInterpolation->interpolate($message);
            $message = $this->requestInterpolation->interpolate($message);
            $this->log( config('request-logger.logger.level', 'info') , $message, [
                self::LOG_CONTEXT
            ]);
        }
    }

}