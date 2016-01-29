<?php namespace Prettus\RequestLogger\Helpers;

/**
 * Class ResponseInterpolation
 * @package Prettus\RequestLogger\Helpers
 */
class ResponseInterpolation extends BaseInterpolation {

    /**
     * @param string $text
     * @return string
     */
    public function interpolate($text)
    {
        $variables = explode(" ",$text);

        foreach( $variables as $variable ) {
            $matches = [];
            preg_match("/{\s*(.+?)\s*}(\r?\n)?/", $variable, $matches);
            if( isset($matches[1]) ) {
                $value =  $this->escape($this->resolveVariable($matches[0], $matches[1]));
                $text = str_replace($matches[0], $value, $text);
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

        if( method_exists($this->response, $method) ) {
            return $this->response->$method();
        } elseif( method_exists($this, $method) ) {
            return $this->$method();
        } else {
            $matches = [];
            preg_match("/([-\w]{2,})(?:\[([^\]]+)\])?/", $variable, $matches);

            if( count($matches) == 3 ) {
                list($line, $var, $option) = $matches;

                switch(strtolower($var)) {
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
    public function getContentLength()
    {

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
    public function responseTime()
    {
        try{
            return Benchmarking::duration('application');
        }catch (\Exception $e){
            return null;
        }
    }
}
