<?php
namespace Prettus\RequestLogger\Middlewares;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Prettus\RequestLogger\Jobs\LogTask;

use Closure;

class ResponseLoggerMiddleware
{
    use DispatchesJobs;

    public function handle($request, Closure $next)
    {
        return $next($request);
    }

    public function terminate($request, $response)
    {
        $task = new LogTask($request, $response);

        if(true === config('request-logger.queue')) {            
            $this->dispatch($task);
        } else {
            $task->handle();
        }
    }

}
