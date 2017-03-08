KATANA SDK for PHP5
=========================

PHP5 SDK to interface with the **KATANA**™ framework (https://katana.kusanagi.io).

Requirements
------------

* KATANA Framework 1.0+
* [libzmq](http://zeromq.org)
* [zmq extension](https://github.com/mkoppanen/php-zmq)
* [msgpack extension](https://github.com/msgpack/msgpack-php)

Installation
------------

The PHP5 SDK can be installed using [composer](https://getcomposer.org/).

```
composer require kusanagi/katana-sdk-php5
```

Getting Started
---------------

The SDK allow both **Services** and **Middlewares** to be created. Both of them require a source file and a configuration file pointing to it.

The first thing to do in the source file is to include the autoloader, then define the actions and run the component.

The following example illustrates how to create a **Service**. Given a configuration file like the following, defining a **Service** with an action and pointing to the PHP source code file:

```yaml
"@context": urn:katana:service
name: service_name
version: "0.1"
http-base-path: /0.1
info:
  title: Example Service
engine:
  runner: urn:katana:runner:php5
  path: ./example_service.php
action:
  - name: action_name
    http-path: /action/path
```

This configuration will run a source code file located in the same directory and named `example_service.php` like the following:

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

The following example illustrates how to create a request **middleware**. Given a configuration file like the following, defining a **Middleware** with both a request and a response, and pointing to the PHP source code file:

```yaml
"@context": urn:katana:middleware
name: middleware_name
version: "0.1"
request: true
response: true
info:
  title: Example Middleware
engine:
  runner: urn:katana:runner:php5
  path: ./example_middleware.php
```

This configuration will run a source code file located in the same directory and named `example_middleware.php` like the following:

```php
<?php

require __DIR__ . '/vendor/autoload.php';

$middleware = new \Katana\Sdk\Middleware();

$middleware->request(function (\Katana\Sdk\Request $request) {
    $request->log('Start Request');

    return $request;
});

$middleware->response(function (\Katana\Sdk\Response $request) {
    $request->log('Start Response');

    return $request;
});

$middleware->run();
```

Examples
--------

One common responsibility of the request **Middlewares** is routing request to the **Service** actions. For this the **Middleware** should set the target **Service**, version and action.

```php
<?php

require __DIR__ . '/vendor/autoload.php';

$middleware = new \Katana\Sdk\Middleware();

$middleware->request(function (\Katana\Sdk\Request $request) {
    $request->setServiceName('service');
    $request->setServiceVersion('1.0.0');
    $request->setActionName('action');

    return $request;
});
```

Response **Middleware** commonly format the data in the transport to present a response.

```php
<?php

require __DIR__ . '/vendor/autoload.php';

$middleware = new \Katana\Sdk\Middleware();

$middleware->response(function (\Katana\Sdk\Response $response) {
    $httpResponse = $response->getHttpResponse();
    $httpResponse->setBody(
        json_encode(
            $response->getTransport()->getData()
        )
    );
    $httpResponse->setStatus(200, 'OK');

    return $response;
});
```

A **Service** can be used to group some related functionality, like a CRUD for a business model.

```php
<?php

require __DIR__ . '/vendor/autoload.php';

$service = new \Katana\Sdk\Service();

$service->action('read', function (\Katana\Sdk\Action $action) {
    $entity = $repository->get($action->getParam('id')->getValue());
    $action->setEntity($entity);

    return $action;
});

$service->action('delete', function (\Katana\Sdk\Action $action) {
    $entity = $repository->delete($action->getParam('id')->getValue());

    return $action;
});

$service->action('create', function (\Katana\Sdk\Action $action) {
    $repository->create(array_map(function (\Katana\Sdk\Api\Param $param) {
        return $param->getValue();
    }, $action->getParams()));

    return $action;
});

$service->action('update', function (\Katana\Sdk\Action $action) {
    $repository->update(array_map(function (\Katana\Sdk\Api\Param $param) {
        return $param->getValue();
    }, $action->getParams()));

    return $action;
});

$service->run();
```

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