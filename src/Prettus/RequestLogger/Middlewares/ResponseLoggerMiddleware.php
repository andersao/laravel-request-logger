<?php
namespace Prettus\RequestLogger\Middlewares;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Prettus\RequestLogger\Jobs\LogTask;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Closure;
use Route;

class ResponseLoggerMiddleware
{
    use DispatchesJobs;

    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }

    public function terminate(Request $request, Response $response)
    {
        // For some reason $request->route() returns null...        
/*        $currentRoute = Route::getCurrentRoute();

        \Log::debug($currentRoute->getPath(). " ". print_r($currentRoute->getMethods(), true));
*/

        if(!$this->excluded($request)) {                    
            $task = new LogTask($request, $response);

            if($queueName = config('request-logger.queue')) {
                $this->dispatch(is_string($queueName) ? $task->onQueue($queueName) : $task);
            } else {
                $task->handle();
            }
        }
    }

    protected function excluded(Request $request) {
        $exclude = config('request-logger.exclude');
        if($exclude){
            foreach($exclude as $path) {
                if($request->is($path)) return true;
            }
        }

        return false;
    }
}
