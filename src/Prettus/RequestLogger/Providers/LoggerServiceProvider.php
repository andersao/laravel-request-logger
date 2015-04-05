<?php namespace Prettus\RequestLogger\Providers;

use Illuminate\Support\ServiceProvider;
use Prettus\RequestLogger\Helpers\Benchmarking;

/**
 * Class LoggerServiceProvider
 * @package Prettus\RequestLogger\Providers
 */
class LoggerServiceProvider extends ServiceProvider {

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
        app('router')->before(function(){
            Benchmarking::start('application');
        });

        app('router')->after(function(){
            Benchmarking::end('application');
        });

        app('router')->after('Prettus\\RequestLogger\\Filters\\ResponseLogger');
    }

}