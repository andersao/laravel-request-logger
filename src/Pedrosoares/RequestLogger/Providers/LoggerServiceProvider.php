<?php

namespace Prettus\RequestLogger\Providers;

use Illuminate\Foundation\Http\Events\RequestHandled;
use Illuminate\Http\Request;
use Prettus\RequestLogger\Helpers\Benchmarking;
use Prettus\RequestLogger\Jobs\LogTask;
use Prettus\RequestLogger\Jobs\Compatibility\LogTask51;
use Prettus\RequestLogger\Jobs\Compatibility\LogTask53;
use Prettus\RequestLogger\Jobs\Compatibility\LumenLogTask;
use Symfony\Component\HttpFoundation\Response;
use Prettus\RequestLogger\Events\KernelHandled;

/**
 * Class LoggerServiceProvider
 * @package Prettus\RequestLogger\Providers
 * @author Anderson Andrade <contato@andersonandra.de>
 */
class LoggerServiceProvider extends LocalProvider {

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../../resources/config/request-logger.php' => config_path('request-logger.php')
        ]);

        $this->mergeConfigFrom(
            __DIR__ . '/../../../resources/config/request-logger.php', 'request-logger'
        );
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Benchmarking::start('application');

        $event_handler = $this->app['events'];

        if(class_exists("Prettus\RequestLogger\Events\KernelHandled")) {
            $event_handler->listen(KernelHandled::class, function (KernelHandled $event) {
                $request = $event->request;
                $response = $event->response;

                $this->onKernelHandled($request, $response);
            });
        }else if(class_exists("Illuminate\Foundation\Http\Events\RequestHandled")){
            $event_handler->listen(RequestHandled::class, function (RequestHandled $event) {
                $request = $event->request;
                $response = $event->response;

                $this->onKernelHandled($request, $response);
            });
        }else{
            $event_handler->listen('kernel.handled', function($request, $response) {
                $this->onKernelHandled($request, $response);
            });
        }
    }

    protected function onKernelHandled($request, $response){
        Benchmarking::end('application');

        $version = $this->getAppVersion();

        if(!$this->excluded($request)) {

            if( version_compare($version, "5.2.99", "<=")) {
                //Compatible with Laravel 5.1 and 5.2
                $task = new LogTask51($request, $response);
            }else if( version_compare($version, "5.3.99", "<=") ){
                //Compatible with Laravel 5.3
                $task = new LogTask53($request, $response);
            }else{
                if(!LoggerServiceProvider::isLumen()) {
                    //Compatible with Laravel 5.4 or later
                    $task = new LogTask($request, $response);
                }else{
                    $task = new LumenLogTask($request, $response);
                }
            }

            if($queueName = config('request-logger.queue')) {
                $this->dispatch(is_string($queueName) ? $task->onQueue($queueName) : $task);
            } else {
                $task->handle();
            }
        }
    }

    protected function excluded(Request $request) {
        $exclude = config('request-logger.exclude');

        if (null === $exclude || empty($exclude)) {
            return false;
        }

        foreach($exclude as $path) {
            if($request->is($path)) return true;
        }

        return false;
    }


    private function getAppVersion() {
        $version = $this->app->version();
        if (substr($version, 0, 7) === 'Lumen (') {
            $version = array_first(explode(')', str_replace('Lumen (', '', $version)));
        }
        return $version;
    }

    public static function isLumen(){
        $version = app()->version();
        return substr($version, 0, 7) === 'Lumen (';
    }

}
