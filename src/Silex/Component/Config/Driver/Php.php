<?php


namespace Silex\Component\Config\Driver;

/**
 * Class Php
 * @package Silex\Component\Config\Driver
 */
class Php extends AbstractConfigDriver
{
    /**
     * @param $filename
     */
    public function __construct($filename)
    {
        $this->data = require_once $filename;
    }
}

// EOF
