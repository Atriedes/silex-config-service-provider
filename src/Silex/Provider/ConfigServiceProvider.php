<?php

namespace Silex\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Component\Config\Driver\Php;
use Silex\Component\Config\Driver\Yaml;

/**
 * Class ConfigServiceProvider
 * @package Silex\Provider
 */
class ConfigServiceProvider implements ServiceProviderInterface
{
    /**
     * @var string
     */
    private $filename;

    /**
     * @var string
     */
    private $extension;

    /**
     * @param $filename
     */
    public function __construct($filename)
    {
        $this->filename = $filename;

        list($file, $ext) = explode(".", basename($filename));
        
        $this->extension = $ext;
    }

    /**
     * @param Container $container
     */
    public function register(Container $container)
    {
        $container["config.factory.php"] =  function (Container $container) {
            return new Php(
                $this->filename,
                $container["cache.factory"],
                $container["config.cache.lifetime"]
            );
        };

        $container["config.factory.yml"] =  function (Container $container) {
            return new Yaml(
                $this->filename,
                $container["cache.factory"],
                $container["config.cache.lifetime"]
            );
        };

        // create cacheable factory service for config
        $container["config"] = function () use ($container) {
            if (! file_exists($this->filename)) {
                throw new \InvalidArgumentException(
                    sprintf("The config file '%s' does not exist.", $this->filename)
                );
            }

            if (! ($container->offsetExists("cache.factory")
                && $container->offsetExists("config.cache.lifetime"))
            ) {
                $container["config.cache.lifetime"] = null;
                $container["cache.factory"] = null;
            }

            return $container["config.factory.".$this->extension];
        };
    }
}
