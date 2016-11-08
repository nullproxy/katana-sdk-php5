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
     * @param callable $errorCallback
     */
    public function execute(
        ApiFactory $factory,
        CliInput $input,
        array $callbacks,
        callable $errorCallback = null
    ) {
        $command = json_decode($input->getInput(), true);

        $action = $input->getAction();
        if (!isset($callbacks[$action])) {
            return $this->sendError("Unregistered callback {$input->getAction()}");
        }

        $api = $factory->build($action, $command, $input);
        $this->executeCallback($api, $action, $callbacks, $errorCallback);
    }
}
