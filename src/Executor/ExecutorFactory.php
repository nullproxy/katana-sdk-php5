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

use Katana\Sdk\Api\Mapper\CompactPayloadMapper;
use Katana\Sdk\Console\CliInput;
use Katana\Sdk\Messaging\MessagePackSerializer;
use Katana\Sdk\Messaging\Responder\JsonResponder;
use Katana\Sdk\Messaging\Responder\ZeroMqMultipartResponder;
use MKraemer\ReactPCNTL\PCNTL;
use React\EventLoop\Factory;
use React\ZMQ\Context;
use ZMQ;

/**
 * Builds an executor to process request
 *
 * @package Katana\Sdk\Console
 */
class ExecutorFactory
{
    /**
     * @param CliInput $input
     * @return AbstractExecutor
     */
    public function build(CliInput $input)
    {
        if ($input->hasInput()) {
            return new InputExecutor(
                new JsonResponder(),
                new CompactPayloadMapper()
            );

        } else {
            $loop = Factory::create();
            $context = new Context($loop);

            $socket = $context->getSocket(ZMQ::SOCKET_REP);

            $pcntl = new PCNTL($loop);
            $socket->bind("ipc://{$input->getSocket()}");

            $pcntl->on(SIGINT, function () use ($socket, $loop, $input) {
                $socket->unbind("ipc://{$input->getSocket()}");
                $loop->stop();
            });

            $pcntl->on(SIGTERM, function () use ($socket, $loop, $input) {
                $socket->unbind("ipc://{$input->getSocket()}");
                $loop->stop();
            });

            $serializer = new MessagePackSerializer();
            $responder = new ZeroMqMultipartResponder($serializer, $socket);

            return new ZeroMqLoopExecutor(
                $loop,
                $socket,
                $serializer,
                $responder,
                new CompactPayloadMapper()
            );
        }
    }
}
