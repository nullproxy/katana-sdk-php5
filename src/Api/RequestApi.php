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
use Katana\Sdk\Component\AbstractComponent;
use Katana\Sdk\Request;

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
     * Response constructor.
     * @param AbstractComponent $component
     * @param string $path
     * @param string $name
     * @param string $version
     * @param string $platformVersion
     * @param array $variables
     * @param bool $debug
     * @param HttpRequest $httpRequest
     * @param ServiceCall $call
     */
    public function __construct(
        AbstractComponent $component,
        $path,
        $name,
        $version,
        $platformVersion,
        array $variables,
        $debug,
        HttpRequest $httpRequest,
        ServiceCall $call
    ) {
        parent::__construct(
            $component,
            $path,
            $name,
            $version,
            $platformVersion,
            $variables,
            $debug
        );
        $this->httpRequest = $httpRequest;
        $this->call = $call;
    }

    /**
     * @param string $method
     * @return bool
     */
    public function isMethod($method)
    {
        return $this->httpRequest->isMethod($method);
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->httpRequest->getMethod();
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->httpRequest->getUrl();
    }

    /**
     * @return string
     */
    public function getUrlScheme()
    {
        return $this->httpRequest->getUrlScheme();
    }

    /**
     * @return string
     */
    public function getUrlHost()
    {
        return $this->httpRequest->getUrlHost();
    }

    /**
     * @return string
     */
    public function getUrlPath()
    {
        return $this->httpRequest->getUrlPath();
    }

    /**
     * @param $name
     * @return bool
     */
    public function hasQueryParam($name)
    {
        return $this->httpRequest->hasQueryParam($name);
    }

    /**
     * @param string $name
     * @param string $default
     * @return string
     */
    public function getQueryParam($name, $default = '')
    {
        return $this->httpRequest->getQueryParam($name, $default);
    }

    /**
     * @param string $name
     * @param array $default
     * @return array
     */
    public function getQueryParamArray($name, $default = [])
    {
        return $this->httpRequest->getQueryParamArray($name, $default);
    }

    /**
     * @return array
     */
    public function getQueryParams()
    {
        return $this->httpRequest->getQueryParams();
    }

    /**
     * @return array
     */
    public function getQueryParamsArray()
    {
        return $this->httpRequest->getQueryParamsArray();
    }

    /**
     * @param $name
     * @return bool
     */
    public function hasPostParam($name)
    {
        return $this->httpRequest->hasPostParam($name);
    }

    /**
     * @param string $name
     * @param string $default
     * @return string
     */
    public function getPostParam($name, $default = '')
    {
        return $this->httpRequest->getPostParam($name, $default);
    }

    /**
     * @param string $name
     * @param array $default
     * @return array
     */
    public function getPostParamArray($name, $default = [])
    {
        return $this->httpRequest->getPostParamArray($name, $default);
    }

    /**
     * @return array
     */
    public function getPostParams()
    {
        return $this->httpRequest->getPostParams();
    }

    /**
     * @return array
     */
    public function getPostParamsArray()
    {
        return $this->httpRequest->getPostParamsArray();
    }

    /**
     * @param string $version
     * @return bool
     */
    public function isProtocolVersion($version)
    {
        return $this->httpRequest->isProtocolVersion($version);
    }

    /**
     * @return string
     */
    public function getProtocolVersion()
    {
        return $this->httpRequest->getProtocolVersion();
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasHeader($name)
    {
        return $this->httpRequest->hasHeader($name);
    }

    /**
     * @param string $name
     * @param string $default
     * @return string
     */
    public function getHeader($name, $default = '')
    {
        return $this->httpRequest->getHeader($name, $default);
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->httpRequest->getHeaders();
    }

    /**
     * @return bool
     */
    public function hasBody()
    {
        return $this->httpRequest->hasBody();
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->httpRequest->getBody();
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
     * @return bool
     */
    public function setServiceName($service)
    {
        $this->call->setService($service);

        return true;
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
     * @return bool
     */
    public function setServiceVersion($version)
    {
        $this->call->setVersion($version);

        return true;
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
     * @return bool
     */
    public function setActionName($action)
    {
        $this->call->setAction($action);

        return true;
    }

    /**
     * @param int $code
     * @param string $text
     * @return ResponseApi
     */
    public function newResponse($code, $text)
    {
        return new ResponseApi(
            $this->component,
            $this->path,
            $this->name,
            $this->version,
            $this->platformVersion,
            $this->variables,
            $this->debug,
            new HttpResponse(
                $this->httpRequest->getProtocolVersion(),
                new HttpStatus($code, $text),
                ''
            ),
            Transport::newEmpty()
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
}
