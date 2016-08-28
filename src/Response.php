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

use Katana\Sdk\Api\TransportReader;

interface Response
{
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
     * @param string $version
     */
    public function setProtocolVersion($version);

    /**
     * @param string $status
     * @return bool
     */
    public function isStatus($status);

    /**
     * @return string
     */
    public function getStatus();

    /**
     * @return int
     */
    public function getStatusCode();

    /**
     * @return string
     */
    public function getStatusText();

    /**
     * @param int $code
     * @param string $text
     */
    public function setStatus($code, $text);

    /**
     * @param string $header
     * @return bool
     */
    public function hasHeader($header);

    /**
     * @param string $header
     * @return string
     */
    public function getHeader($header);

    /**
     * @return array
     */
    public function getHeaders();

    /**
     * @param string $header
     * @param string $value
     */
    public function setHeader($header, $value);

    /**
     * @return bool
     */
    public function hasBody();

    /**
     * @return string
     */
    public function getBody();

    /**
     * @param string $content
     */
    public function setBody($content);

    /**
     * @return TransportReader
     */
    public function getTransport();
}
