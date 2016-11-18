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
use Katana\Sdk\Exception\ConsoleException;

/**
 * Processes cli input
 *
 * @package Katana\Sdk\Console
 */
class CliInput
{
    const MAPPINGS = [
        'compact',
        'extended',
    ];

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
    private $mapping;

    /**
     * @var string
     */
    private $input = '';

    /**
     * @var string
     */
    private $action = '';

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
            'platform-version' => new CliOption('p', 'platform-version', CliOption::VALUE_SINGLE),
            'socket' => new CliOption('s', 'socket', CliOption::VALUE_SINGLE),
            'debug' => new CliOption('D', 'debug', CliOption::VALUE_NONE),
            'var' => new CliOption('V', 'var', CliOption::VALUE_MULTIPLE),
            'disable-compact-names' => new CliOption('d', 'disable-compact-names', CliOption::VALUE_NONE),
            'input' => new CliOption('i', 'input', CliOption::VALUE_SINGLE),
            'action' => new CliOption('a', 'action', CliOption::VALUE_SINGLE),
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
            $optionValues['platform-version'],
            $optionValues['socket'],
            $optionValues['debug'],
            $optionValues['var'],
            $optionValues['disable-compact-names'] ? 'extended' : 'compact',
            $optionValues['input'],
            $optionValues['action']
        );
    }

    /**
     * CliInput constructor.
     * @param string $component
     * @param string $name
     * @param string $version
     * @param string $platformVersion
     * @param string $socket
     * @param bool $debug
     * @param array $variables
     * @param string $input
     * @param string $action
     */
    public function __construct(
        $component,
        $name,
        $version,
        $platformVersion,
        $socket = '',
        $debug = false,
        array $variables = [],
        $mapping = 'compact',
        $input = '',
        $action = ''
    ) {
        $this->component = $component;
        $this->name = $name;
        $this->version = $version;
        $this->platformVersion = $platformVersion;
        $socketVersion = preg_replace('/[^a-z0-9]/i', '-', $version);
        $this->socket = $socket ?: "@katana-$component-$name-$socketVersion";
        $this->debug = $debug;
        $this->variables = $variables;
        $this->variables = $variables;
        if (!in_array($mapping, self::MAPPINGS)) {
            throw new ConsoleException("Invalid mapping $mapping");
        }
        $this->mapping = $mapping;
        if ($input && file_exists($input)) {
            $this->input = file_get_contents($input);
        }
        $this->action = $action;
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
    public function getMapping()
    {
        return $this->mapping;
    }

    /**
     * @return string
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }
}
