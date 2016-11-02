<?php

namespace Prettus\RequestLogger\Jobs;

use App\Jobs\Job;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogTask extends Job implements ShouldQueue
{

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
