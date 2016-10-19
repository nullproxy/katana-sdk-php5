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
 * Support Api class that encapsulates a service call
 *
 * @package Katana\Sdk\Api
 */
class Call
{
    /**
     * @var ServiceOrigin
     */
    private $origin;

    /**
     * @var string
     */
    private $service;

    /**
     * @var string
     */
    private $version;

    /**
     * @var string
     */
    private $action;

    /**
     * @var Param[]
     */
    private $params = [];

    /**
     * @var File[]
     */
    private $files = [];

    /**
     * Call constructor.
     * @param ServiceOrigin $origin
     * @param string $service
     * @param string $version
     * @param string $action
     * @param Param[] $params
     * @param File[] $files
     */
    public function __construct(
        ServiceOrigin $origin,
        $service,
        $version,
        $action,
        array $params = [],
        array $files = []
    ) {
        $this->origin = $origin;
        $this->service = $service;
        $this->version = $version;
        $this->action = $action;
        $this->params = $params;
        $this->files = $files;
    }

    /**
     * @return ServiceOrigin
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * @return string
     */
    public function getService()
    {
        return $this->service;
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
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return Param[]
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @return File[]
     */
    public function getFiles()
    {
        return $this->files;
    }
}
