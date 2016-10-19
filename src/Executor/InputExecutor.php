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
use Katana\Sdk\Console\CliInput;

/**
 * Executor that gets a single input from cli
 *
 * @package Katana\Sdk\Executor
 */
class InputExecutor extends AbstractExecutor
{
    /**
     * @param ApiFactory $factory
     * @param CliInput $input
     * @param callable[] $callbacks
     */
    public function execute(
        ApiFactory $factory,
        CliInput $input,
        array $callbacks
    ) {
        // todo: can't be implemented with current spec.
//        $command = json_decode($input->getInput(), true);
//        $action = $factory->build($command, $input);
//        $response = $callable($action);
//
//        $this->responder->sendResponse($response, $this->mapper);
    }
}
