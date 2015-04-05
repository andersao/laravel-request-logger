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
        if( config('request-logger.response.enabled') )
        {
            $message = config('request-logger.response.format', "{ip} {remote_user} {date} {method} {url} HTTP/{http_version} {status} {content_length} {referrer} {user_agent}");
            $message = $this->interpolate($message, $request, $response);
            $this->log( config('request-logger.logger.level', 'info') , $message, [
                self::LOG_CONTEXT,
                $response->getStatusCode()
            ]);
        }
    }

    /**
     * @param $text
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    protected function interpolate($text, Request $request, Response $response){

        return str_replace(array(
            "{content}",
            "{content_length}",
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
            "{query_string}",
            "{remote_user}",
            "{user_agent}",
            "{referrer}",
            "{date}",
        ), array(
            $response->getContent(),
            $this->getContentLength($response),
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
            $request->getQueryString(),
            $request->getUser(),
            $request->server('HTTP_USER_AGENT'),
            $request->server('HTTP_REFERER'),
            date('Y-m-d H:i:s'),
        ), $text);

    }

    /**
     * @param Response $response
     * @return int
     */
    protected function getContentLength(Response $response){

        $path = storage_path("framework".DIRECTORY_SEPARATOR."temp");

        if( !file_exists($path)){
            mkdir($path, 0777, true);
        }

        $content = $response->getContent();
        $file    = $path.DIRECTORY_SEPARATOR."response-".time();
        file_put_contents($file, $content);
        $content_length = filesize($file);
        unlink($file);

        return $content_length;
    }
}