<?php

namespace Prettus\RequestLogger\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Prettus\RequestLogger\Helpers\Benchmarking;
use Prettus\RequestLogger\Jobs\LogTask;
use Prettus\RequestLogger\Jobs\Compatibility\LogTask51;
use Prettus\RequestLogger\Jobs\Compatibility\LogTask53;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Http\Events\RequestHandled;

/**
 * Class LoggerServiceProvider
 * @package Prettus\RequestLogger\Providers
 * @author Anderson Andrade <contato@andersonandra.de>
 */
class LoggerServiceProvider extends ServiceProvider
{
    use DispatchesJobs;

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

        $this->app['events']->listen(RequestHandled::class, function ($event) {
            Benchmarking::end('application');

            if(!$this->excluded($event->request)) {

                if( version_compare($this->app->version(), "5.2.99", "<=")) {
                    //Compatible with Laravel 5.1 and 5.2
                    $task = new LogTask51($event->request, $event->response);
                }else if( version_compare($this->app->version(), "5.3.99", "<=") ){
                    //Compatible with Laravel 5.3
                    $task = new LogTask53($event->request, $event->response);
                }else{
                    //Compatible with Laravel 5.4 or later
                    $task = new LogTask($event->request, $event->response);
                }

                if($queueName = config('request-logger.queue')) {
                    $this->dispatch(is_string($queueName) ? $task->onQueue($queueName) : $task);
                } else {
                    $task->handle();
                }
            }
        });

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

}
