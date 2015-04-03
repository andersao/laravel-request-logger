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
                "{status_code}",
                "{charset}",
                "{protocol_version}",
                "{is_cacheable}",
                "{is_empty}",
                "{is_client_error}",
                "{is_forbidden}",
                "{is_not_found}",
                "{is_ok}",
                "{is_invalid}",
                "{is_redirection}",
            ), array(
                $response->getContent(),
                $response->getStatusCode(),
                $response->getCharset(),
                $response->getProtocolVersion(),
                $response->isCacheable() ? "true" : "false",
                $response->isEmpty() ? "true" : "false",
                $response->isClientError() ? "true" : "false",
                $response->isForbidden() ? "true" : "false",
                $response->isNotFound() ? "true" : "false",
                $response->isOk() ? "true" : "false",
                $response->isInvalid() ? "true" : "false",
                $response->isRedirection() ? "true" : "false",
            ), $format);

            $this->info($format, [self::LOG_CONTEXT]);
        }
    }
}