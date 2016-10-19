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

/**
 * Support Transport Api class that encapsulates transport meta data.
 * @package Katana\Sdk\Api
 */
class TransportMeta
{
    /**
     * Version of the platform
     *
     * @var string
     */
    private $version;

    /**
     * Unique id of the request
     *
     * @var string
     */
    private $id;

    /**
     * Datetime of the process in UTC and ISO 8601
     *
     * @var string
     */
    private $datetime;

    /**
     * Origin service for the request
     *
     * @var array
     */
    private $origin = [];

    /**
     * The depth of the service requests during the request
     *
     * MUST begin at 1 and increment with the length of chained calls
     *
     * @var integer
     */
    private $level;

    /**
     * Custom user land properties
     *
     * @var array
     */
    private $properties = [];

    /**
     * @param string $version
     * @param string $id
     * @param string $datetime
     * @param array $origin
     * @param int $level
     * @param array $properties
     */
    public function __construct(
        $version,
        $id,
        $datetime,
        $origin,
        $level,
        array $properties = []
    ) {
        $this->version = $version;
        $this->id = $id;
        $this->datetime = $datetime;
        $this->origin = $origin;
        $this->level = $level;
        $this->properties = $properties;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getDatetime()
    {
        return $this->datetime;
    }

    /**
     * @return array
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getProperty($name)
    {
        return $this->properties[$name];
    }

    /**
     * @param string $name
     * @param $value
     */
    public function setProperty($name, $value)
    {
        $this->properties[$name] = $value;
    }

    /**
     * @return bool
     */
    public function hasProperties()
    {
        return !empty($this->properties);
    }

    /**
     * @return array
     */
    public function getProperties()
    {
        return $this->properties;
    }
}
