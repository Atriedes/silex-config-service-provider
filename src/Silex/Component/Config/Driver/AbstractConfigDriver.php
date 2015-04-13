<?php


namespace Silex\Component\Config\Driver;

use Doctrine\Common\Cache\Cache;

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
     * @var Cache $cache
     */
    protected $cache;

    /**
     * @car int $cache_lifetime
     */
    protected $cache_lifetime;

    /**
     * @var string
     */
    protected $cache_namespace;

    /**
     * @param string $filename
     * @param Cache $cache
     * @param int $cache_lifetime
     */
    abstract function __construct($filename, Cache $cache = null, $cache_lifetime = 300);

    /**
     * @param string $path
     * @param string|null $default
     * @return null|array|string
     */
    public function get($path, $default = null)
    {
        // retrieve value from cache
        if (isset($this->cache) && $this->cache->contains($this->cache_namespace.$path)) {
            return unserialize($this->cache->fetch($this->cache_namespace.$path));
        }

        $paths = explode("/", $path);

        if (empty($paths[0]) || !isset($this->data[$paths[0]])) {
            return false;
        }

        $part = &$this->data;
        $value = null;

        foreach ($paths as $key) {
            if (!isset($part[$key])) {
                $value = null;
                break;
            }

            $value = $part[$key];
            $part = &$part[$key];
        }

        if ($value !== null) {
            // save value to cache server
            if (isset($this->cache)) {
                $this->cache->save($this->cache_namespace.$path, serialize($value), $this->cache_lifetime);
            }
            return $value;
        }

        return $default;
    }

    /**
     * @param string $filename
     * @param Cache $cache
     * @return bool
     */
    protected function retrieveCacheConfig($filename, Cache $cache = null)
    {
        $this->cache_namespace = sha1($filename);
        $this->cache = $cache;

        if (isset($cache) && $cache->contains($this->cache_namespace)) {
            $this->data = unserialize($cache->fetch($this->cache_namespace));
            return true;
        }

        return false;
    }
}

// EOF
