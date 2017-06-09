<?php

namespace Prettus\RequestLogger\Jobs;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class LogTask implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $request;
    protected $response;

    /**
     * Create a new job instance.
     *
     * @param  Request  $request
     * @param  Response $response
     * @return void
     */
    public function __construct($request, $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * Execute the job.
     *
     * @param  Mailer  $mailer
     * @return void
     */
    public function handle()
    {
        $requestLogger = app(\Prettus\RequestLogger\ResponseLogger::class);
        $requestLogger->log($this->request, $this->response);
    }
}