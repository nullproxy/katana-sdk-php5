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
use Katana\Sdk\Component\AbstractComponent;

/**
 * Base class for Api classes.
 *
 * @package Katana\Sdk\Api
 */
abstract class Api
{
    /**
     * @var AbstractComponent
     */
    protected $component;

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
     * @param AbstractComponent $component
     * @param string $path
     * @param string $name
     * @param string $version
     * @param string $platformVersion
     * @param array $variables
     * @param bool $debug
     */
    public function __construct(
        AbstractComponent $component,
        $path,
        $name,
        $version,
        $platformVersion,
        array $variables = [],
        $debug = false
    ) {
        $this->component = $component;
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

    /**
     * @param string $name
     * @return bool
     */
    public function hasResource($name)
    {
        return $this->component->hasResource($name);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getResource($name)
    {
        return $this->component->getResource($name);
    }
}
