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

use Katana\Sdk\Api\Api;
use Katana\Sdk\Api\Factory\ApiFactory;
use Katana\Sdk\Api\Mapper\PayloadWriterInterface;
use Katana\Sdk\Console\CliInput;
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
     */
    public function __construct(
        $loop,
        $socket,
        MessagePackSerializer $serializer,
        ResponderInterface $responder,
        PayloadWriterInterface $mapper
    ) {
        $this->loop = $loop;
        $this->socket = $socket;
        $this->serializer = $serializer;
        parent::__construct($responder, $mapper);
    }

    /**
     * @param ApiFactory $factory
     * @param CliInput $input
     * @param callable $callable
     */
    public function execute(
        ApiFactory $factory,
        CliInput $input,
        callable $callable
    ) {
        $this->socket->on(
            'message',
            function ($payload) use ($callable, $factory, $input) {
                $msg = new MessagePackSerializer();
                $command = $msg->unserialize($payload);

                $api = $factory->build($command, $input);

                $response = $callable($api);
                if (!$response instanceof Api) {
                    throw new \Exception('Wrong response');
                }

                $this->responder->sendResponse($response, $this->mapper);
            }
        );

        $this->loop->run();
    }
}
