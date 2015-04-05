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
        if( config('request-logger.request.enabled') )
        {
            $message = config('request-logger.request.format', "{ip} {remote_user} {date} {method} {url} {referrer} {user_agent}");
            $message = $this->interpolate($message, $request);
            $this->log( config('request-logger.logger.level', 'info') , $message, [self::LOG_CONTEXT ]);
        }
    }

    /**
     * @param $text
     * @param Request $request
     * @return mixed
     */
    protected function interpolate($text, Request $request){

        return str_replace(array(
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
            "{query_string}",
            "{remote_user}",
            "{user_agent}",
            "{referrer}",
            "{date}"
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
            $request->getQueryString(),
            $request->getUser(),
            $request->server('HTTP_USER_AGENT'),
            $request->server('HTTP_REFERER'),
            date('Y-m-d H:i:s'),
        ), $text);

    }
}