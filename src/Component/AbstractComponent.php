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

use Katana\Sdk\Console\CliInput;
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

    public function __construct()
    {
        $this->logger = new KatanaLogger();
        //$this->logger->debug('And it all begins');
        $this->input = CliInput::createFromCli();
        $this->executor = (new ExecutorFactory())->build($this->input);
    }
}
