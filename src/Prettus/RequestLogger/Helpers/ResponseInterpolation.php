<?php namespace Prettus\RequestLogger\Helpers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Prettus\RequestLogger\Contracts\Interpolable;

/**
 * Class ResponseInterpolation
 * @package Prettus\RequestLogger\Helpers
 */
class ResponseInterpolation implements Interpolable {

    /**
     * @var Response
     */
    protected $response = null;

    /**
     * @var Request
     */
    protected $request = null;

    /**
     * @param Response $response
     */
    public function setResponse($response)
    {
        $this->response = $response;
    }

    /**
     * @param Request $request
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }

    /**
     * @param string $text
     * @return string
     */
    public function interpolate($text){

        $variables = explode(" ",$text);

        foreach( $variables as $variable )
        {
            $output = [];
            preg_match("/{\s*(.+?)\s*}(\r?\n)?/", $variable, $output);
            if( isset($output[1]) )
            {
                $value = $this->resolveVariable($output[0], $output[1]);
                $text = str_replace($output[0], $value, $text);
            }
        }

        return $text;
    }

    /**
     * @param $raw
     * @param $variable
     * @return string
     */
    public function resolveVariable($raw, $variable)
    {
        $method = str_replace([
            "content",
            "httpVersion",
            "status",
            "statusCode"
        ], [
            "getContent",
            "getProtocolVersion",
            "getStatusCode",
            "getStatusCode"
        ],camel_case($variable));

        if( method_exists($this->response, $method) )
        {
            return $this->response->$method();
        }
        elseif( method_exists($this, $method) )
        {
            return $this->$method();
        }
        else
        {
            $output = [];
            preg_match("/([-\w]{2,})(?:\[([^\]]+)\])?/", $variable, $output);

            if( is_array($output) && count($output) == 3 )
            {
                list($line, $var, $option) = $output;

                switch(strtolower($var))
                {
                    case "res":
                        return $this->response->headers->get($option);
                    default;
                        return $raw;
                }
            }
        }

        return $raw;
    }

    /**
     * @return int
     */
    public function getContentLength(){

        $path = storage_path("framework".DIRECTORY_SEPARATOR."temp");

        if( !file_exists($path)){
            mkdir($path, 0777, true);
        }

        $content = $this->response->getContent();
        $file    = $path.DIRECTORY_SEPARATOR."response-".time();
        file_put_contents($file, $content);
        $content_length = filesize($file);
        unlink($file);

        return $content_length;
    }

    /**
     * @return float|null
     */
    public function responseTime(){
        try{
            return Benchmarking::duration('application');
        }catch (\Exception $e){
            return null;
        }
    }
}