<?php

namespace Prettus\RequestLogger\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Prettus\RequestLogger\Helpers\Benchmarking;
use Prettus\RequestLogger\Jobs\LogTask;
use Illuminate\Foundation\Bus\DispatchesJobs;

/**
 * Class LoggerServiceProvider
 * @package Prettus\RequestLogger\Providers
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

        $this->app['events']->listen('kernel.handled', function ($request, $response) {

            Benchmarking::end('application');

            if(!$this->excluded($request)) {
                $task = new LogTask($request, $response);

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
