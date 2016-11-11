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

namespace Katana\Sdk\Logger;

/**
 * Logger class
 *
 * @package Katana\Sdk\Logger
 */
class KatanaLogger
{
    const FORMAT = '%TIMESTAMP% [%TYPE%] [SDK] %MESSAGE% %REQUEST_ID%';

    /**
     * @var bool
     */
    private $debug = false;

    /**
     * @param bool $debug
     */
    public function __construct($debug = false)
    {
        $this->debug = $debug;
    }

    /**
     * @return bool|string
     */
    private function getTimestamp()
    {
        list($usec, $sec) = explode(" ", microtime());
        return sprintf(
            "%s.%dZ",
            (new \DateTime("@$sec"))->format('Y-m-d\TH:i:s'),
            round($usec * 100000)
        );
    }

    /**
     * @param string $level
     * @param string $message
     * @param string $requestId
     */
    private function log($level, $message, $requestId = '')
    {
        if ($level === 'DEBUG' && !$this->debug) {
            return;
        }

        $requestId = $requestId? "|$requestId|" : '';
        echo trim(str_replace(
            ['%TIMESTAMP%', '%TYPE%', '%MESSAGE%', '%REQUEST_ID%'],
            [$this->getTimestamp(), $level, $message, $requestId],
            self::FORMAT
        )), "\n";
    }

    /**
     * @param string $message
     * @param string $requestId
     */
    public function debug($message, $requestId = '')
    {
        $this->log('DEBUG', $message, $requestId);
    }

    /**
     * @param string $message
     * @param string $requestId
     */
    public function info($message, $requestId = '')
    {
        $this->log('INFO', $message, $requestId);
    }

    /**
     * @param string $message
     * @param string $requestId
     */
    public function warning($message, $requestId = '')
    {
        $this->log('WARNING', $message, $requestId);
    }

    /**
     * @param string $message
     * @param string $requestId
     */
    public function error($message, $requestId = '')
    {
        $this->log('ERROR', $message, $requestId);
    }
}
