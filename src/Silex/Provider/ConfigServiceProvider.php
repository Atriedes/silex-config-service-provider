<?php


namespace Silex\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Component\Config\Driver\Php;
use Silex\Component\Config\Driver\Yaml;

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

        list($path, $ext) = explode(".", $filename);
        
        $this->extension = $ext;
    }

    /**
     * @param Container $container
     */
    public function register(Container $container)
    {
        $container["config"] = function () use ($container) {
            if (! file_exists($this->filename)) {
                throw new \InvalidArgumentException(
                    sprintf("The config file '%s' does not exist.", $this->filename)
                );
            }

            switch ($this->extension) {
                case "php":
                    $driver = new Php($this->filename);
                    break;
                case "yml":
                    $driver =  new Yaml($this->filename);
                    break;
                default:
                    throw new \InvalidArgumentException("Unknown driver for processing config file");
                    break;
            }

            return $driver;
        };
    }
}

// EOF
