<?php


namespace Jowy\Test;

use Pimple\Container;
use Silex\Provider\CacheServiceProvider;
use Silex\Provider\ConfigServiceProvider;

/**
 * Class ConfigServiceProviderTest
 * @package Jowy\Test
 */
class ConfigServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Container
     */
    private $app;

    /**
     * constructor
     */
    public function __construct()
    {
        $this->app = new Container();
    }

    /**
     * test yaml config
     */
    public function testYmlConfig()
    {
        $this->app->register(new ConfigServiceProvider(__DIR__ . "/../Test/config.yml"));

        $this->assertEquals("value", $this->app["config"]->get("main/key"));
    }

    /**
     * test php array config
     */
    public function testPhpConfig()
    {
        $this->app->register(new ConfigServiceProvider(__DIR__ . "/config.php"));

        $this->assertEquals("value", $this->app["config"]->get("main/key"));
    }

    /**
     * test cached config value
     */
    public function testCachedPhpConfig()
    {
        $this->app->register(
            new ConfigServiceProvider(__DIR__ . "/config.php"),
            [
                "config.cache.lifetime" => 300
            ]
        );

        $this->app->register(
            new CacheServiceProvider(),
            [
                "cache.driver" => "filesystem",
                "cache.options" => [
                    "namespace" => "jowy.config",
                    "directory" => __DIR__ . "/Cache"
                ]
            ]
        );

        /**
         * retrieve from file
         */
        $this->assertEquals("value", $this->app["config"]->get("main/key"));

        /**
         * retrieve from cache
         */
        $this->assertEquals("value", $this->app["config"]->get("main/key"));
    }

    public function testCachedYmlConfig()
    {
        $this->app->register(
            new ConfigServiceProvider(__DIR__ . "/config.yml"),
            [
                "config.cache.lifetime" => 300
            ]
        );

        $this->app->register(
            new CacheServiceProvider(),
            [
                "cache.driver" => "filesystem",
                "cache.options" => [
                    "namespace" => "jowy.config",
                    "directory" => __DIR__ . "/Cache"
                ]
            ]
        );

        /**
         * retrieve from file
         */
        $this->assertEquals("value", $this->app["config"]->get("main/key"));

        /**
         * retrieve from cache
         */
        $this->assertEquals("value", $this->app["config"]->get("main/key"));
    }

    public function testRedisCachedConfig()
    {
        if (! extension_loaded("redis")) {
            return;
        }

        $this->app->register(
            new ConfigServiceProvider(__DIR__ . "/config.yml"),
            [
                "config.cache.lifetime" => 300
            ]
        );

        $this->app->register(
            new CacheServiceProvider(),
            [
                "cache.driver" => "redis",
                "cache.options" => [
                    "namespace" => "jowy.config",
                    "host"  => "127.0.0.1",
                    "port"      => 6379
                ]
            ]
        );

        /**
         * retrieve from file
         */
        $this->assertEquals("value", $this->app["config"]->get("main/key"));

        /**
         * retrieve from cache
         */
        $this->assertEquals("value", $this->app["config"]->get("main/key"));
    }
}

// EOF
