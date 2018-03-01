<?php
/**
 * Created by PhpStorm.
 * User: pedrosoares
 * Date: 2/28/18
 * Time: 3:04 PM
 */

namespace Prettus\RequestLogger\Events;

use Illuminate\Queue\SerializesModels;
use Prettus\RequestLogger\Providers\LoggerServiceProvider;
use Symfony\Component\HttpFoundation\Response;

if(LoggerServiceProvider::isLumen()) {
    class KernelHandled {

        use SerializesModels;

        public $request;
        public $response;

        public function __construct()
        {
            $this->request = app('request');
            $this->response = app(Response::class);
        }

    }
}