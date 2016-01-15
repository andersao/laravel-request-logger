<?php
namespace Prettus\RequestLogger\Middlewares;

use Closure;

class ResponseLoggerMiddleware
{

    public function handle($request, Closure $next)
    {
        return $next($request);
    }

    public function terminate($request, $response)
    {
        $requestLogger = app(\Prettus\RequestLogger\ResponseLogger::class);
        $requestLogger->log($request, $response);
    }

}
