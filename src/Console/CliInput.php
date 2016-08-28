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

namespace Katana\Sdk\Console;

/**
 * Processes cli input
 *
 * @package Katana\Sdk\Console
 */
class CliInput
{
    /**
     * Type of component
     *
     * @var string
     */
    private $component;

    /**
     * Name of the Component
     *
     * @var string
     */
    private $name;

    /**
     * Version of the component
     *
     * @var string
     */
    private $version;

    /**
     * Name of the action for Service Components only
     *
     * @var string
     */
    private $action;

    /**
     * Version of the Katana platform
     *
     * @var string
     */
    private $platformVersion;

    /**
     * Socket name for ZeroMQ to open an IPC socket
     *
     * @var string
     */
    private $socket;

    /**
     * Debug mode
     *
     * @var boolean
     */
    private $debug;

    /**
     * @var string
     */
    private $input = '';

    /**
     * Arbitrary variables as key/value string pairs
     *
     * @var array
     */
    private $variables = [];

    public static function createFromCli()
    {
        $definition = [
            'component' => new CliOption('c', 'component', CliOption::VALUE_SINGLE),
            'name' => new CliOption('n', 'name', CliOption::VALUE_SINGLE),
            'version' => new CliOption('v', 'version', CliOption::VALUE_SINGLE),
            'action' => new CliOption('a', 'action', CliOption::VALUE_SINGLE),
            'platform-version' => new CliOption('p', 'platform-version', CliOption::VALUE_SINGLE),
            'socket' => new CliOption('s', 'socket', CliOption::VALUE_SINGLE),
            'debug' => new CliOption('D', 'debug', CliOption::VALUE_NONE),
            'var' => new CliOption('V', 'var', CliOption::VALUE_MULTIPLE),
            'input' => new CliOption('i', 'input', CliOption::VALUE_SINGLE),
        ];

        $shortOpts = '';
        $longOpts = [];
        /** @var CliOption $option */
        foreach ($definition as $option) {
            if ($option->getShortDefinition()) {
                $shortOpts .= $option->getShortDefinition();
            }

            if ($option->getLongDefinition()) {
                $longOpts[] = $option->getLongDefinition();
            }
        }

        $options = getopt($shortOpts, $longOpts);

        $optionValues = array_map(function (CliOption $option) use ($options) {
            return $option->parse($options);
        }, $definition);

        return new self(
            $optionValues['component'],
            $optionValues['name'],
            $optionValues['version'],
            $optionValues['action'],
            $optionValues['platform-version'],
            $optionValues['socket'],
            $optionValues['debug'],
            $optionValues['var'],
            $optionValues['input']
        );
    }

    /**
     * CliInput constructor.
     * @param string $component
     * @param string $name
     * @param string $version
     * @param string $action
     * @param string $platformVersion
     * @param string $socket
     * @param bool $debug
     * @param array $variables
     * @param string $input
     */
    public function __construct(
        $component,
        $name,
        $version,
        $action,
        $platformVersion,
        $socket = '',
        $debug = false,
        array $variables = [],
        $input = ''
    ) {
        $this->component = $component;
        $this->name = $name;
        $this->version = $version;
        $this->action = $action;
        $this->platformVersion = $platformVersion;
        $this->socket = $socket ?: "@katana-$component-$name-$action";
        $this->debug = $debug;
        $this->variables = $variables;
        if ($input && file_exists($input)) {
            $this->input = file_get_contents($input);
        }
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
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return string
     */
    public function getPlatformVersion()
    {
        return $this->platformVersion;
    }

    /**
     * @return string
     */
    public function getSocket()
    {
        return $this->socket;
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
    public function hasVariable($name)
    {
        return isset($this->variables[$name]);
    }

    /**
     * @param string $name
     * @return string
     */
    public function getVariable($name)
    {
        if (!$this->hasVariable($name)) {
            return '';
        }

        return $this->variables['name'];
    }

    public function getVariables()
    {
        return $this->variables;
    }

    /**
     * @return bool
     */
    public function hasInput()
    {
        return !empty($this->input);
    }

    /**
     * @return string
     */
    public function getInput()
    {
        return $this->input;
    }
}
