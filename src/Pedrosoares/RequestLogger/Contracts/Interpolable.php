<?php namespace Prettus\RequestLogger\Contracts;

/**
 * Interface Interpolable
 * @package Prettus\RequestLogger\Contracts
 * @author Anderson Andrade <contato@andersonandra.de>
 */
interface Interpolable {

    /**
     * @param string $text
     * @return string
     */
    public function interpolate($text);
}
