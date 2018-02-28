<?php
/**
 * Created by PhpStorm.
 * User: pedrosoares
 * Date: 2/28/18
 * Time: 4:27 PM
 */

namespace Prettus\RequestLogger\Jobs\Compatibility;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class LumenLogTask
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $request;
    protected $response;

    /**
     * LogTask constructor.
     * @param $request
     * @param $response
     */
    public function __construct($request, $response)
    {
        $this->request  = $request;
        $this->response = $response;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $requestLogger = app(\Prettus\RequestLogger\ResponseLogger::class);
        $requestLogger->log($this->request, $this->response);
    }
}