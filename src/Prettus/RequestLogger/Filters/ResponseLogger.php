<?php namespace Prettus\RequestLogger\Filters;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
     * @var array
     */
    protected $formats = [
        "combined"  =>'{remote-addr} - {remote-user} [{date}] "{method} {url} HTTP/{http-version}" {status} {content-length} "{referrer}" "{user-agent}"',
        "common"    =>'{remote-addr} - {remote-user} [{date}] "{method} {url} HTTP/{http-version}" {status} {content-length}',
        "dev"       =>'{method} {url} {status} {response-time} ms - {content-length}',
        "short"     =>'{remote-addr} {remote-user} {method} {url} HTTP/{http-version} {status} {content-length} - {response-time} ms',
        "tiny"      =>'{method} {url} {status} {content-length} - {response-time} ms'
    ];

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
        parent::__construct();
    }

    /**
     *
     */
    const LOG_CONTEXT = "RESPONSE";

    /**
     *
     */
    public function filter(Response $response)
    {
        $this->responseInterpolation->setResponse($response);

        if( config('request-logger.logger.enabled') )
        {
            $message = config('request-logger.logger.format', "{ip} {remote_user} {date} {method} {url} HTTP/{http_version} {status} {content_length} {referrer} {user_agent}");
            $message = isset($this->formats[$message]) ? $this->formats[$message] : $message;
            $message = $this->responseInterpolation->interpolate($message);
            $message = $this->requestInterpolation->interpolate($message);
            $this->log( config('request-logger.logger.level', 'info') , $message, [
                self::LOG_CONTEXT
            ]);
        }
    }

}