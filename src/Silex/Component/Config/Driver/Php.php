<?php


namespace Silex\Component\Config\Driver;

use Doctrine\Common\Cache\Cache;

/**
 * Class Php
 * @package Silex\Component\Config\Driver
 */
class Php extends AbstractConfigDriver
{
    /**
     * {@inheritdoc}
     */
    public function __construct($filename, Cache $cache = null, $cache_lifetime = 300)
    {
        $this->cache_lifetime = $cache_lifetime;
        if (! $this->retrieveCacheConfig($cache)) {
            $this->data = require $filename;

            if (! is_null($cache)) {
                $cache->save("config:", serialize($this->data), $cache_lifetime);
            }
        }
    }
}

// EOF
