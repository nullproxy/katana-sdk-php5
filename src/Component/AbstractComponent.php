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

namespace Katana\Sdk\Component;

use Katana\Sdk\Api\Factory\ApiFactory;
use Katana\Sdk\Console\CliInput;
use Katana\Sdk\Exception\ConsoleException;
use Katana\Sdk\Executor\AbstractExecutor;
use Katana\Sdk\Executor\ExecutorFactory;
use Katana\Sdk\Logger\KatanaLogger;

/**
 * Base class for Components
 *
 * @package Katana\Sdk\Component
 */
abstract class AbstractComponent
{
    /**
     * @var CliInput
     */
    protected $input;

    /**
     * @var AbstractExecutor
     */
    protected $executor;

    /**
     * @var KatanaLogger
     */
    protected $logger;

    /**
     * @var callback[]
     */
    private $callbacks = [];

    /**
     * @var array
     */
    private $resources = [];

    /**
     * @var callable
     */
    private $startup;

    /**
     * @var callable
     */
    private $error;

    /**
     * @var callable
     */
    private $shutdown;

    public function __construct()
    {
        $this->input = CliInput::createFromCli();
        $this->logger = new KatanaLogger($this->input->isDebug());
        $this->executor = (new ExecutorFactory($this->logger))->build($this->input);
    }

    /**
     * @param string $name
     * @param callable $callback
     */
    protected function setCallback($name, callable $callback)
    {
        $this->callbacks[$name] = $callback;
    }

    /**
     * Run the SDK.
     */
    public function run()
    {
        if ($this->startup) {
            $this->logger->debug('Calling startup callback');
            $return = call_user_func($this->startup, $this);
            if (!$return instanceof static) {
                $msg = 'Wrong return for startup';
                $this->logger->error($msg);
                throw new ConsoleException($msg);
            }
        }

        $actions = implode(', ', array_keys($this->callbacks));
        $this->logger->info("Component running with callbacks for $actions");
        $this->executor->execute(
            $this->getApiFactory(),
            $this->input,
            $this->callbacks,
            $this->error
        );

        if ($this->shutdown) {
            $this->logger->debug('Calling shutdown callback');
            $return = call_user_func($this->shutdown, $this);
            if (!$return instanceof static) {
                $msg = 'Wrong return for startup';
                $this->logger->error($msg);
                throw new ConsoleException($msg);
            }
        }
    }

    /**
     * @return ApiFactory
     */
    abstract protected function getApiFactory();

    /**
     * @param string $name
     * @param callable $resource
     * @return bool
     * @throws ConsoleException
     */
    public function setResource($name, callable $resource)
    {
        $resource = $resource();
        if (!$resource) {
            $msg = "Set resource $name failed";
            $this->logger->error($msg);
            throw new ConsoleException($msg);
        }

        $this->logger->info("Setting $name resource");
        $this->resources[$name] = $resource;

        return true;
    }

    /**
     * @param string $name
     * @return mixed
     * @throws ConsoleException
     */
    public function getResource($name)
    {
        if (!$this->hasResource($name)) {
            $msg = "Resource $name not found";
            $this->logger->error($msg);
            throw new ConsoleException($msg);
        }

        return $this->resources[$name];
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasResource($name)
    {
        return isset($this->resources[$name]);
    }

    /**
     * @param callable $startup
     * @return bool
     */
    public function startup(callable $startup)
    {
        $this->startup = $startup;

        return true;
    }

    /**
     * @param callable $shutdown
     * @return bool
     */
    public function shutdown(callable $shutdown)
    {
        $this->shutdown = $shutdown;

        return true;
    }

    /**
     * @param callable $error
     * @return bool
     */
    public function error(callable $error)
    {
        $this->error = $error;

        return true;
    }
}
