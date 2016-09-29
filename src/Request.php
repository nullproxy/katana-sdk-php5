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

interface Request extends ApiInterface
{
    /**
     * @param string $method
     * @return bool
     */
    public function isMethod($method);

    /**
     * @return string
     */
    public function getMethod();

    /**
     * @return string
     */
    public function getUrl();

    /**
     * @return string
     */
    public function getUrlScheme();

    /**
     * @return string
     */
    public function getUrlHost();

    /**
     * @return string
     */
    public function getUrlPath();

    /**
     * @param $name
     * @return bool
     */
    public function hasQueryParam($name);

    /**
     * @param string $name
     * @param string $default
     * @return string
     */
    public function getQueryParam($name, $default = '');

    /**
     * @param string $name
     * @param array $default
     * @return array
     */
    public function getQueryParamArray($name, $default = []);

    /**
     * @return array
     */
    public function getQueryParams();

    /**
     * @return array
     */
    public function getQueryParamsArray();

    /**
     * @param $name
     * @return bool
     */
    public function hasPostParam($name);

    /**
     * @param string $name
     * @param string $default
     * @return string
     */
    public function getPostParam($name, $default = '');

    /**
     * @param string $name
     * @param array $default
     * @return array
     */
    public function getPostParamArray($name, $default = []);

    /**
     * @return array
     */
    public function getPostParams();

    /**
     * @return array
     */
    public function getPostParamsArray();

    /**
     * @param string $version
     * @return bool
     */
    public function isProtocolVersion($version);

    /**
     * @return string
     */
    public function getProtocolVersion();

    /**
     * @param string $name
     * @return bool
     */
    public function hasHeader($name);

    /**
     * @param string $name
     * @param string $default
     * @return string
     */
    public function getHeader($name, $default = '');

    /**
     * @return array
     */
    public function getHeaders();

    /**
     * @return bool
     */
    public function hasBody();

    /**
     * @return string
     */
    public function getBody();

    /**
     * @return string
     */
    public function getServiceName();

    /**
     * @param string $service
     */
    public function setServiceName($service);

    /**
     * @return string
     */
    public function getServiceVersion();

    /**
     * @param string $version
     */
    public function setServiceVersion($version);

    /**
     * @return string
     */
    public function getActionName();

    /**
     * @param string $action
     */
    public function setActionName($action);

    /**
     * @param int $code
     * @param string $text
     * @return Response
     */
    public function newResponse($code, $text);
}
