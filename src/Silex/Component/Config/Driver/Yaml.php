<?php


namespace Silex\Component\Config\Driver;

/**
 * Class Yaml
 * @package Silex\Component\Config\Driver
 */
class Yaml extends AbstractConfigDriver
{
    /**
     * @param $filename
     */
    public function __construct($filename)
    {
        $this->data = \Symfony\Component\Yaml\Yaml::parse($filename);
    }
}

// EOF
