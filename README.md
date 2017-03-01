KATANA SDK for PHP5
=========================

[badges]

PHP5 SDK to interface with the **KATANA**™ framework (https://katana.kusanagi.io).

Requirements
------------

* KATANA Framework 1.0+
* [ZeroMQ](http://zeromq.org/)
* [zmq extension](https://github.com/mkoppanen/php-zmq)
* [msgpack extension](https://github.com/msgpack/msgpack-php)

Installation
------------

The PHP5 SDK can be installed using [composer](https://getcomposer.org/).

```
composer install kusanagi/katana-sdk-php5
```

*Installation instructions for the SDK, such as package manager, running tests, etc*

Configuration
-------------

*(optional) Configuration of the SDK, including engine variables, environment variables or other configuration options*

Getting Started
---------------

The SDK allow both **services** and **middlewares** to be created. Both of them require a source file and a configuration file pointing to it.

The first thing to do in the source file is to include the autoloader, then define the actions and run the component.


The following example illustrate how to create a **service**.

```php
<?php

require __DIR__ . '/vendor/autoload.php';

$service = new \Katana\Sdk\Service();

$service->action('action_name', function (\Katana\Sdk\Action $action) {
    $action->log('Start action');
    
    return $action;
});

$service->run();
```

The following example illustrate how to create a request **middleware**.

```php
<?php

require __DIR__ . '/vendor/autoload.php';

$middleware = new \Katana\Sdk\Middleware();

$middleware->request(function (\Katana\Sdk\Request $request) {
    $request->log('Start Request');
    
    return $request;
});

$middleware->run();
```

*Brief example of how to use the SDK in the implementation language*

Examples
--------

One common responsibility of the request **middlewares** is routing request to the **service** actions. For this the **middleware** should set the target **service**, version and action.

```php
<?php

$middleware->request(function (\Katana\Sdk\Request $request) {
    $request->setServiceName('service');
    $request->setServiceVersion('1.0.0');
    $request->setActionName('action');
    
    return $request;
});

$middleware->run();
```

Response **middlewares** commonly format the data in the transport to present a response.

```php
<?php

$middleware->response(function (\Katana\Sdk\Response $response) {
    
    return $response;
});

$middleware->run();
```

*(optional) Any relevant examples to help with development*

Documentation
-------------

See the [API](https://kusanagi.io/app#katana/docs/sdk) for a technical reference of the SDK, or read the full [specification](https://kusanagi.io/app#katana/docs/sdk/specification).

For help using the framework see the [documentation](https://kusanagi.io/app#katana/docs), or join the [community](https://kusanagi.io/app#katana/community).

Support
-------

Please first read our [contribution guidelines](https://kusanagi.io/app#katana/open-source/contributing).

* [Requesting help](https://kusanagi.io/app#katana/open-source/help)
* [Reporting a bug](https://kusanagi.io/app#katana/open-source/bug)
* [Submitting a patch](https://kusanagi.io/app#katana/open-source/patch)
* [Security issues](https://kusanagi.io/app#katana/open-source/security)

We use [milestones](https://github.com/kusanagi/katana-sdk-php5/milestones) to track upcoming releases inline with our [versioning](https://kusanagi.io/app#katana/versioning) strategy, and as defined in our [roadmap](https://kusanagi.io/app#katana/roadmap).

For commercial support see the [solutions](https://kusanagi.io/solutions) available or [contact us](https://kusanagi.io/contact) for more information.

Contributing
------------

If you'd like to know how you can help and support our Open Source efforts see the many ways to [get involved](https://kusanagi.io/app#katana/open-source/get-involved).

Please also be sure to review our [community guidelines](https://kusanagi.io/app#katana/community/conduct).

License
-------

Copyright 2016-2017 KUSANAGI S.L. (https://kusanagi.io). All rights reserved.

KUSANAGI, the sword logo, KATANA and the "K" logo are trademarks and/or registered trademarks of KUSANAGI S.L. All other trademarks are property of their respective owners.

Licensed under the [MIT License](https://kusanagi.io/app#katana/open-source/license). Redistributions of the source code included in this repository must retain the copyright notice found in each file.