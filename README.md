## Silex Config Service provider

Provide Configuration Service for Silex

### Usage

#### Installation

For yaml based config

~~~php

$this->app->register(new ConfigServiceProvider("path/to/config.yml"));

~~~

For php array based config

~~~php

$this->app->register(new ConfigServiceProvider("path/to/config.php"));

~~~

#### Access Config

~~~php
$app["config"]->get("key/subkey/subsubkey");
~~~

### License

MIT, see LICENSE