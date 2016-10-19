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

use Katana\Sdk\Api\Factory\ApiFactory;
use Katana\Sdk\Api\Mapper\PayloadWriterInterface;
use Katana\Sdk\Console\CliInput;
use Katana\Sdk\Messaging\Responder\ResponderInterface;

/**
 * Base class for component executors
 *
 * @package Katana\Sdk\Executor
 */
abstract class AbstractExecutor
{
    /**
     * @var ResponderInterface
     */
    protected $responder;

    /**
     * @var PayloadWriterInterface
     */
    protected $mapper;

    /**
     * @param PayloadWriterInterface $mapper
     * @param ResponderInterface $responder
     */
    public function __construct(
        ResponderInterface $responder,
        PayloadWriterInterface $mapper
    ) {
        $this->responder = $responder;
        $this->mapper = $mapper;
    }

    /**
     * @param ApiFactory $factory
     * @param CliInput $input
     * @param callable[] $callbacks
     */
    abstract public function execute(
        ApiFactory $factory,
        CliInput $input,
        array $callbacks
    );
}
