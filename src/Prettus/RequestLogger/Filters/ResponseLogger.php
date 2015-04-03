<?php namespace Prettus\RequestLogger\Filters;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Prettus\RequestLogger\Logger;

/**
 * Class Logger
 * @package Prettus\Logger\Request
 */
class ResponseLogger extends Logger
{
    /**
     *
     */
    const LOG_CONTEXT = "RESPONSE";

    /**
     * @param Request $request
     * @param Response $response
     */
    public function filter(Request $request, Response $response)
    {
        if( config('request-logger.response.enabled', false) )
        {
            $format = config('request-logger.response.format', "[{status_code}] {content}");
            $format = str_replace(array(
                "{content}",
                "{status}",
                "{http_version}",
                "{method}",
                "{root}",
                "{url}",
                "{fullUrl}",
                "{path}",
                "{decodedPath}",
                "{ip}",
                "{format}",
                "{scheme}",
                "{port}",
                "{query_string}"
            ), array(
                $response->getContent(),
                $response->getStatusCode(),
                $response->getProtocolVersion(),
                $request->method(),
                $request->root(),
                $request->url(),
                $request->fullUrl(),
                $request->path(),
                $request->decodedPath(),
                $request->ip(),
                $request->format(),
                $request->getScheme(),
                $request->getPort(),
                $request->getQueryString()
            ), $format);

            $this->log( config('request-logger.logger.level', 'info') , $format, [self::LOG_CONTEXT ]);
        }
    }
}