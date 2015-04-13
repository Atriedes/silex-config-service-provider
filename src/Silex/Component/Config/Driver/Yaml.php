<?php


namespace Silex\Component\Config\Driver;

use Doctrine\Common\Cache\Cache;

/**
 * Class Yaml
 * @package Silex\Component\Config\Driver
 */
class Yaml extends AbstractConfigDriver
{
    /**
     * {@inheritdoc}
     */
    public function __construct($filename, Cache $cache = null, $cache_lifetime = 300)
    {
        $this->cache_lifetime = $cache_lifetime;
        if (! $this->retrieveCacheConfig($filename, $cache)) {
            $this->data = \Symfony\Component\Yaml\Yaml::parse($filename);

            if (! is_null($cache)) {
                $cache->save(sha1($filename), serialize($this->data), $cache_lifetime);
            }
        }
    }
}

// EOF
