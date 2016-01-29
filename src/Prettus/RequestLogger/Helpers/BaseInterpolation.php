<?php namespace Prettus\RequestLogger\Helpers;

use Prettus\RequestLogger\Contracts\Interpolable;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class BaseInterpolation
 * @package Prettus\RequestLogger\Helpers
 */
abstract class BaseInterpolation implements Interpolable {

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;    
    
    /**
     * @param Request $request
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param Response $response
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;
    }

    /**
     * @param string $raw
     */
    protected function escape($raw)
    {
        return preg_replace('/\s/', "\\s", $raw);
    }
}