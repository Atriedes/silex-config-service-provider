<?php


namespace Jowy\Test;

use Pimple\Container;
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
}

// EOF
