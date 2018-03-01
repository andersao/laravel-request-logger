<?php
/**
 * Created by PhpStorm.
 * User: pedrosoares
 * Date: 2/28/18
 * Time: 4:47 PM
 */

namespace Prettus\RequestLogger\Providers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\ServiceProvider;


if(class_exists('DispatchesJobs')){
    class LocalProvider extends ServiceProvider {
        use DispatchesJobs;
    };
}else{
    class LocalProvider  extends ServiceProvider {};
}