<?php namespace Prettus\RequestLogger\Helpers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Prettus\RequestLogger\Contracts\Interpolable;

/**
 * Class RequestInterpolation
 * @package Prettus\RequestLogger\Helpers
 */
class RequestInterpolation implements Interpolable {

    /**
     * @var Request
     */
    protected $request;

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
            "remoteAddr",
            "scheme",
            "port",
            "queryString",
            "remoteUser"
        ], [
            "ip",
            "getScheme",
            "getPort",
            "getQueryString",
            "getUser"
        ],camel_case($variable));

        if( method_exists($this->request, $method) )
        {
            return $this->request->$method();
        }
        elseif( isset($_SERVER["HTTP_".strtoupper($variable)]) )
        {
            return $this->request->server("HTTP_".strtoupper($variable));
        }
        else
        {
            $output = [];
            preg_match("/([-\w]{2,})(?:\[([^\]]+)\])?/", $variable, $output);

            if( count($output) == 2 )
            {
                switch($output[0])
                {
                    case "date": $output[] = "clf"; break;
                }
            }

            if( is_array($output) && count($output) == 3 )
            {
                list($line, $var, $option) = $output;

                switch(strtolower($var))
                {
                    case "date":

                        $formats = [
                            "clf"=>Carbon::now()->format("d/M/Y:H:i:s O"),
                            "iso"=>Carbon::now()->toIso8601String(),
                            "web"=>Carbon::now()->toRfc1123String()
                        ];

                        return isset($formats[$option]) ? $formats[$option] : Carbon::now()->format($option);

                    case "req":
                    case "header":
                        return $this->request->header(strtolower($option));
                    case "server":
                        return $this->request->server($option);
                    default;
                        return $line;
                }
            }
        }

        return $raw;
    }
}