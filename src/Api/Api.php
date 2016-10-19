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
 * Base class for Api classes.
 *
 * @package Katana\Sdk\Api
 */
abstract class Api
{
    /**
     * @var string
     */
    protected $path;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $version;

    /**
     * @var string
     */
    protected $platformVersion;

    /**
     * @var array
     */
    protected $variables = [];

    /**
     * @var bool
     */
    protected $debug = false;

    /**
     * @param string $path
     * @param string $name
     * @param string $version
     * @param string $platformVersion
     * @param array $variables
     * @param bool $debug
     */
    public function __construct(
        $path,
        $name,
        $version,
        $platformVersion,
        array $variables = [],
        $debug = false
    ) {
        $this->path = $path;
        $this->name = $name;
        $this->version = $version;
        $this->platformVersion = $platformVersion;
        $this->variables = $variables;
        $this->debug = $debug;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
    public function getPlatformVersion()
    {
        return $this->platformVersion;
    }

    /**
     * @return array
     */
    public function getVariables()
    {
        return $this->variables;
    }

    /**
     * @param $name
     * @return string
     */
    public function getVariable($name)
    {
        if (!isset($this->variables[$name])) {
            return '';
        }

        return $this->variables[$name];
    }

    /**
     * @return boolean
     */
    public function isDebug()
    {
        return $this->debug;
    }
}
