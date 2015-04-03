<?php namespace Prettus\RequestLogger\Filters;

use Illuminate\Http\Request;
use Prettus\RequestLogger\Logger;

/**
 * Class Logger
 * @package Prettus\Logger\Request
 */
class RequestLogger extends Logger
{
    /**
     *
     */
    const LOG_CONTEXT = "REQUEST";

    /**
     * @param Request $request
     */
    public function filter(Request $request)
    {
        if( config('request-logger.request.enabled', false) )
        {
            $format = config('request-logger.request.format', "[{method}] {fullUrl} {ip}");
            $format = str_replace(array(
                "{method}",
                "{root}",
                "{url}",
                "{fullUrl}",
                "{path}",
                "{decodedPath}",
                "{ip}",
                "{remote_addr}",
                "{format}",
                "{scheme}",
                "{port}",
                "{query_string}"
            ),array(
                $request->method(),
                $request->root(),
                $request->url(),
                $request->fullUrl(),
                $request->path(),
                $request->decodedPath(),
                $request->ip(),
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