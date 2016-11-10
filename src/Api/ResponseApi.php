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

use Katana\Sdk\Api\Protocol\Http\HttpResponse;
use Katana\Sdk\Component\AbstractComponent;
use Katana\Sdk\Response;
use Katana\Sdk\Transport as TransportInterface;

class ResponseApi extends Api implements Response
{
    /**
     * @var HttpResponse
     */
    private $response;

    /**
     * @var Transport
     */
    private $transport;

    /**
     * Response constructor.
     * @param AbstractComponent $component
     * @param string $path
     * @param string $name
     * @param string $version
     * @param string $platformVersion
     * @param array $variables
     * @param bool $debug
     * @param HttpResponse $response
     * @param Transport $transport
     */
    public function __construct(
        AbstractComponent $component,
        $path,
        $name,
        $version,
        $platformVersion,
        array $variables,
        $debug,
        HttpResponse $response,
        Transport $transport
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
        $this->response = $response;
        $this->transport = $transport;
    }

    /**
     * @param string $version
     * @return bool
     */
    public function isProtocolVersion($version)
    {
        return $version === $this->response->getProtocolVersion();
    }

    /**
     * @return string
     */
    public function getProtocolVersion()
    {
        return $this->response->getProtocolVersion();
    }

    /**
     * @param string $version
     * @return bool
     */
    public function setProtocolVersion($version)
    {
        $this->response->setProtocolVersion($version);

        return true;
    }

    /**
     * @param string $status
     * @return bool
     */
    public function isStatus($status)
    {
        return $this->response->isStatus($status);
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->response->getStatus();
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->response->getStatusCode();
    }

    /**
     * @return string
     */
    public function getStatusText()
    {
        return $this->response->getStatusText();
    }

    /**
     * @param int $code
     * @param string $text
     * @return bool
     */
    public function setStatus($code, $text)
    {
        $this->response->setStatus($code, $text);

        return true;
    }

    /**
     * @param string $header
     * @return bool
     */
    public function hasHeader($header)
    {
        return isset($this->response->getHeaders()[$header]);
    }

    /**
     * @param string $header
     * @return string
     */
    public function getHeader($header)
    {
        return $this->response->hasHeader($header);
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->response->getHeaders();
    }

    /**
     * @param string $header
     * @param string $value
     * @return bool
     */
    public function setHeader($header, $value)
    {
        $this->response->setHeader($header, $value);

        return true;
    }

    /**
     * @return bool
     */
    public function hasBody()
    {
        return !empty($this->response->getBody());
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->response->getBody();
    }

    /**
     * @param string $content
     * @return bool
     */
    public function setBody($content)
    {
        $this->response->setBody($content);

        return true;
    }

    /**
     * @return TransportInterface
     */
    public function getTransport()
    {
        return new TransportReader($this->transport);
    }

    /**
     * @return HttpResponse
     */
    public function getHttpResponse()
    {
        return $this->response;
    }
}
