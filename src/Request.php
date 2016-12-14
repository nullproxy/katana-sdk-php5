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

namespace Katana\Sdk;

use Katana\Sdk\Api\ApiInterface;
use Katana\Sdk\Api\ParamContainerInterface;
use Katana\Sdk\Api\Protocol\Http\HttpRequest;

interface Request extends ApiInterface, ParamContainerInterface
{
    /**
     * @return string
     */
    public function getServiceName();

    /**
     * @param string $service
     * @return Request
     */
    public function setServiceName($service);

    /**
     * @return string
     */
    public function getServiceVersion();

    /**
     * @param string $version
     * @return Request
     */
    public function setServiceVersion($version);

    /**
     * @return string
     */
    public function getActionName();

    /**
     * @param string $action
     * @return Request
     */
    public function setActionName($action);

    /**
     * @param int $code
     * @param string $text
     * @return Response
     */
    public function newResponse($code, $text);

    /**
     * @return HttpRequest
     */
    public function getHttpRequest();

    /**
     * @return string
     */
    public function getGatewayProtocol();
}
