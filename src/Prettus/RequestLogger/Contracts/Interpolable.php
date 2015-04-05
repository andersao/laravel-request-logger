<?php namespace Prettus\RequestLogger\Contracts;

/**
 * Interface Interpolable
 * @package Prettus\RequestLogger\Contracts
 */
interface Interpolable {

    /**
     * @param string $text
     * @return string
     */
    public function interpolate($text);
}