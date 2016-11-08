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

namespace Katana\Sdk\Executor;

use Exception;
use Katana\Sdk\Api\Api;
use Katana\Sdk\Api\Factory\ApiFactory;
use Katana\Sdk\Api\Mapper\PayloadWriterInterface;
use Katana\Sdk\Console\CliInput;
use Katana\Sdk\Logger\KatanaLogger;
use Katana\Sdk\Messaging\MessagePackSerializer;
use Katana\Sdk\Messaging\Responder\ResponderInterface;

/**
 * Executor that sets up an event loop listening to ZeroMQ
 *
 * @package Katana\Sdk\Executor
 */
class ZeroMqLoopExecutor extends AbstractExecutor
{
    private $loop;

    private $socket;

    /**
     * @var MessagePackSerializer
     */
    private $serializer;

    /**
     * @param mixed $loop
     * @param mixed $socket
     * @param MessagePackSerializer $serializer
     * @param ResponderInterface $responder
     * @param PayloadWriterInterface $mapper
     * @param KatanaLogger $logger
     */
    public function __construct(
        $loop,
        $socket,
        MessagePackSerializer $serializer,
        ResponderInterface $responder,
        PayloadWriterInterface $mapper,
        KatanaLogger $logger
    ) {
        $this->loop = $loop;
        $this->socket = $socket;
        $this->serializer = $serializer;
        parent::__construct($responder, $mapper, $logger);
    }

    /**
     * @param ApiFactory $factory
     * @param CliInput $input
     * @param callable[] $callbacks
     * @param callable $errorCallback
     */
    public function execute(
        ApiFactory $factory,
        CliInput $input,
        array $callbacks,
        callable $errorCallback = null
    ) {
        $this->socket->on(
            'messages',
            function ($message) use ($callbacks, $factory, $input, $errorCallback) {

                list($action, $payload) = $message;

                if (!isset($callbacks[$action])) {
                    return $this->sendError("Unregistered callback $action");
                }

                $msg = new MessagePackSerializer();
                $command = $msg->unserialize($payload);

                $api = $factory->build($action, $command, $input);
                $this->executeCallback($api, $action, $callbacks, $errorCallback);

                return true;
            }
        );

        $this->loop->run();
    }
}
