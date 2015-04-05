<?php namespace Prettus\RequestLogger\Helpers;

/**
 * Class Benchmarking
 * @package Prettus\RequestLogger\Helpers
 */
class Benchmarking {

    /**
     * @var array
     */
    protected static $timers = [];

    /**
     * @param $name
     * @return mixed
     */
    public static function start($name){
        $start = microtime(true);

        self::$timers[$name] = [
            'start'=>$start
        ];

        return $start;
    }

    /**
     * @param $name
     * @return float
     * @throws \Exception
     */
    public static function end($name){

        $end = microtime(true);

        if( isset(self::$timers[$name]) && isset(self::$timers[$name]['start']) )
        {
            if( isset(self::$timers[$name]['duration']) ){
                return self::$timers[$name]['duration'];
            }

            $start = self::$timers[$name]['start'];
            self::$timers[$name]['end'] = $end;
            self::$timers[$name]['duration'] = $end - $start;

            return self::$timers[$name]['duration'];
        }

        throw new \Exception("Benchmarking '{$name}' not started");
    }

    /**
     * @param $name
     * @return float
     * @throws \Exception
     */
    public static function duration($name){
        return self::end($name);
    }
}