<?php


namespace Silex\Component\Config\Driver;

/**
 * Class AbstractConfigDriver
 * @package Silex\Component\Config\Driver
 */
abstract class AbstractConfigDriver
{
    /**
     * Config data in array
     * @var array
     */
    protected $data;

    /**
     * @param $filename
     */
    abstract function __construct($filename);

    /**
     * @param $path
     * @param null $default
     * @return null|array|string
     */
    public function get($path, $default = null)
    {
        $path = explode("/", $path);

        if (empty($path[0]) || !isset($this->data[$path[0]])) {
            return false;
        }

        $part = &$this->data;
        $value = null;

        foreach ($path as $key) {
            if (!isset($part[$key])) {
                $value = null;
                break;
            }

            $value = $part[$key];
            $part = &$part[$key];
        }

        if ($value !== null) {
            return $value;
        }

        return $default;
    }
}

// EOF
