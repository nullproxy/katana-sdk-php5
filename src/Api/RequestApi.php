<?php
/**
 * PHP 5 SDK for the KATANA(tm) Platform (http://katana.kusanagi.io)
 * Copyright (c) 2016-2017 KUSANAGI S.L. All rights reserved.
 *
 * Distributed under the MIT license
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 *
 * @link      https://github.com/kusanagi/katana-sdk-php5
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @copyright Copyright (c) 2016-2017 KUSANAGI S.L. (http://kusanagi.io)
 */

namespace Katana\Sdk\Api;

use Katana\Sdk\Api\Protocol\Http\HttpRequest;
use Katana\Sdk\Api\Protocol\Http\HttpResponse;
use Katana\Sdk\Api\Protocol\Http\HttpStatus;
use Katana\Sdk\Api\Value\VersionString;
use Katana\Sdk\Component\Component;
use Katana\Sdk\Logger\KatanaLogger;
use Katana\Sdk\Request;
use Katana\Sdk\Schema\Mapping;

class RequestApi extends Api implements Request
{
    /**
     * @var HttpRequest
     */
    private $httpRequest;

    /**
     * @var ServiceCall
     */
    private $call;

    /**
     * @var string
     */
    private $protocol;

    /**
     * @var string
     */
    private $gatewayAddress;

    /**
     * @var string
     */
    private $client;

    /**
     * Response constructor.
     * @param KatanaLogger $logger
     * @param Component $component
     * @param Mapping $mapping
     * @param string $path
     * @param string $name
     * @param string $version
     * @param string $frameworkVersion
     * @param array $variables
     * @param bool $debug
     * @param HttpRequest $httpRequest
     * @param ServiceCall $call
     * @param string $protocol
     * @param string $gatewayAddress
     * @param $client
     */
    public function __construct(
        KatanaLogger $logger,
        Component $component,
        Mapping $mapping,
        $path,
        $name,
        $version,
        $frameworkVersion,
        array $variables,
        $debug,
        HttpRequest $httpRequest,
        ServiceCall $call,
        $protocol,
        $gatewayAddress,
        $client
    ) {
        parent::__construct(
            $logger,
            $component,
            $mapping,
            $path,
            $name,
            $version,
            $frameworkVersion,
            $variables,
            $debug
        );
        $this->httpRequest = $httpRequest;
        $this->call = $call;
        $this->protocol = $protocol;
        $this->gatewayAddress = $gatewayAddress;
        $this->client = $client;
    }

    /**
     * @return string
     */
    public function getServiceName()
    {
        return $this->call->getService();
    }

    /**
     * @param string $service
     * @return Request
     */
    public function setServiceName($service)
    {
        $this->call->setService($service);

        return $this;
    }

    /**
     * @return string
     */
    public function getServiceVersion()
    {
        return $this->call->getVersion();
    }

    /**
     * @param string $version
     * @return Request
     */
    public function setServiceVersion($version)
    {
        $this->call->setVersion(new VersionString($version));

        return $this;
    }

    /**
     * @return string
     */
    public function getActionName()
    {
        return $this->call->getAction();
    }

    /**
     * @param string $action
     * @return Request
     */
    public function setActionName($action)
    {
        $this->call->setAction($action);

        return $this;
    }

    /**
     * @param int $code
     * @param string $text
     * @return ResponseApi
     */
    public function newResponse($code, $text)
    {
        return new ResponseApi(
            $this->logger,
            $this->component,
            $this->mapping,
            $this->path,
            $this->name,
            $this->version,
            $this->frameworkVersion,
            $this->variables,
            $this->debug,
            $this->httpRequest,
            new HttpResponse(
                $this->httpRequest->getProtocolVersion(),
                new HttpStatus($code, $text),
                ''
            ),
            Transport::newEmpty(),
            $this->protocol,
            $this->gatewayAddress
        );
    }

    /**
     * @return HttpRequest
     */
    public function getHttpRequest()
    {
        return $this->httpRequest;
    }

    /**
     * @return ServiceCall
     */
    public function getServiceCall()
    {
        return $this->call;
    }

    /**
     * @return string
     */
    public function getGatewayProtocol()
    {
        return $this->protocol;
    }

    /**
     * @return string
     */
    public function getGatewayAddress()
    {
        return $this->gatewayAddress;
    }

    /**
     * @return string
     */
    public function getClientAddress()
    {
        return $this->client;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasParam($name)
    {
        return $this->call->hasParam($name);
    }

    /**
     * @param string $name
     * @return Param
     */
    public function getParam($name)
    {
        return $this->call->getParam($name);
    }

    /**
     * @return Param[]
     */
    public function getParams()
    {
        return $this->call->getParams();
    }

    /**
     * @param string $name
     * @param string $value
     * @param string $type
     * @return Param
     */
    public function newParam($name, $value = '', $type = Param::TYPE_STRING)
    {
        return $this->call->newParam($name, $value, $type);
    }

    /**
     * @param Param $param
     * @return Request
     */
    public function setParam(Param $param)
    {
        $this->call->setParam($param);

        return $this;
    }

}
