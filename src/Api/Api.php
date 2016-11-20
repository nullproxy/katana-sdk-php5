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
use Closure;
use Katana\Sdk\Component\AbstractComponent;
use Katana\Sdk\Logger\KatanaLogger;

/**
 * Base class for Api classes.
 *
 * @package Katana\Sdk\Api
 */
abstract class Api
{
    /**
     * @var KatanaLogger
     */
    protected $logger;
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
     * @param KatanaLogger $logger
     * @param AbstractComponent $component
     * @param string $path
     * @param string $name
     * @param string $version
     * @param string $platformVersion
     * @param array $variables
     * @param bool $debug
     */
    public function __construct(
        KatanaLogger $logger,
        AbstractComponent $component,
        $path,
        $name,
        $version,
        $platformVersion,
        array $variables = [],
        $debug = false
    ) {
        $this->logger = $logger;
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

    public function log($value)
    {
        if (!$this->debug) {
            return false;
        }

        if (is_null($value)) {
            $log = 'NULL';
        } elseif (is_string($value)) {
            $log = $value;
        } elseif (is_callable($value)) {
            if ($value instanceof Closure) {
                $log = 'function anonymous';
            } elseif (is_array($value)) {
                list($class, $method) = $value;
                if (is_object($class)) {
                    $class = get_class($class);
                }
                $log = "function $class::$method";
            } else {
                $log = 'Unknown value type';
            }
        } elseif (is_bool($value)) {
            $log = $value? 'TRUE' : 'FALSE';
        } elseif (is_float($value)) {
            $log = rtrim(sprintf('%.9f', $value));
        } elseif (is_array($value)) {
            $log = json_encode($value);
        } elseif (is_int($value) || is_resource($value)) {
            $log = (string) $value;
        } else {
            $log = 'Unknown value type';
        }

        $this->logger->debug(substr($log, 0, 100000));

        return true;
    }
}
