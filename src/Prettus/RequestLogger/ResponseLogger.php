<?php

namespace Prettus\RequestLogger;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Prettus\RequestLogger\Helpers\RequestInterpolation;
use Prettus\RequestLogger\Helpers\ResponseInterpolation;
use Prettus\RequestLogger\Logger;

/**
 * Class Logger
 * @package Prettus\Logger\Request
 */
class ResponseLogger
{
    /**
     *
     */
    const LOG_CONTEXT = "RESPONSE";

    /**
     * @var array
     */
    protected $formats = [
        "combined"  =>'{remote-addr} - {remote-user} [{date}] "{method} {url} HTTP/{http-version}" {status} {content-length} "{referer}" "{user-agent}"',
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
    
    /**
     * @var Logger
     */
    protected $logger;

    public function __construct(Logger $logger, RequestInterpolation $requestInterpolation, ResponseInterpolation $responseInterpolation)
    {
        $this->logger = $logger;
        $this->requestInterpolation = $requestInterpolation;
        $this->responseInterpolation = $responseInterpolation;
    }

    /**
     * @param Request $request
     * @param Response $response
     */
    public function log(Request $request, Response $response)
    {
        $this->responseInterpolation->setResponse($response);
        $this->responseInterpolation->setRequest($request);
        $this->requestInterpolation->setRequest($request);

        if( config('request-logger.logger.enabled') ) {
            $format = config('request-logger.logger.format', "{ip} {remote_user} {date} {method} {url} HTTP/{http_version} {status} {content_length} {referer} {user_agent}");
            $format = array_get($this->formats, $format, $format);
            $message = $this->responseInterpolation->interpolate($format);
            $message = $this->requestInterpolation->interpolate($message);
            $this->logger->log( config('request-logger.logger.level', 'info') , $message, [
                static::LOG_CONTEXT
            ]);
        }
    }

}
