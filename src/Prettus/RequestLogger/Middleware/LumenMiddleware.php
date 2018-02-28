<?php
/**
 * Created by PhpStorm.
 * User: pedrosoares
 * Date: 2/28/18
 * Time: 2:59 PM
 */

namespace Prettus\RequestLogger\Middleware;

use Illuminate\Contracts\Bus\Dispatcher;
use Prettus\RequestLogger\Events\KernelHandled;

class LumenMiddleware {

    protected $kernelBootHasDispatched = false;

    public function handle($request, \Closure $next) {
        $response = $next($request);

        if(!$this->kernelBootHasDispatched){
            $this->kernelBootHasDispatched = false;
            event(new KernelHandled());
        }

        return $response;
    }

}