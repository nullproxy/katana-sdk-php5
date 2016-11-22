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
use Katana\Sdk\Component\AbstractComponent;
use Katana\Sdk\Logger\KatanaLogger;
use Katana\Sdk\Response;
use Katana\Sdk\Transport as TransportInterface;

class ResponseApi extends Api implements Response
{
    /**
     * @var HttpRequest
     */
    private $request;

    /**
     * @var HttpResponse
     */
    private $response;

    /**
     * @var Transport
     */
    private $transport;

    /**
     * @var string
     */
    private $protocol;

    /**
     * Response constructor.
     * @param KatanaLogger $logger
     * @param AbstractComponent $component
     * @param string $path
     * @param string $name
     * @param string $version
     * @param string $platformVersion
     * @param array $variables
     * @param bool $debug
     * @param HttpRequest $request
     * @param HttpResponse $response
     * @param Transport $transport
     * @param string $protocol
     */
    public function __construct(
        KatanaLogger $logger,
        AbstractComponent $component,
        $path,
        $name,
        $version,
        $platformVersion,
        array $variables,
        $debug,
        HttpRequest $request,
        HttpResponse $response,
        Transport $transport,
        $protocol
    ) {
        parent::__construct(
            $logger,
            $component,
            $path,
            $name,
            $version,
            $platformVersion,
            $variables,
            $debug
        );
        $this->request = $request;
        $this->response = $response;
        $this->transport = $transport;
        $this->protocol = $protocol;
    }

    /**
     * @return HttpRequest
     */
    public function getHttpRequest()
    {
        return $this->request;
    }

    /**
     * @return HttpResponse
     */
    public function getHttpResponse()
    {
        return $this->response;
    }

    /**
     * @return TransportInterface
     */
    public function getTransport()
    {
        return new TransportReader($this->transport);
    }

    /**
     * @return string
     */
    public function getGatewayProtocol()
    {
        return $this->protocol;
    }
}
