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

namespace Katana\Sdk\Api\Factory;

use Katana\Sdk\Api\Api;
use Katana\Sdk\Api\Mapper\PayloadReaderInterface;
use Katana\Sdk\Console\CliInput;

/**
 * Provides methods to get factories for any Api class.
 * @package Katana\Sdk\Api\Factory
 */
abstract class ApiFactory
{
    /**
     * Read mapper to translate an input into Api instances.
     *
     * @var PayloadReaderInterface
     */
    protected $mapper;

    /**
     * @param PayloadReaderInterface $mapper
     * @return RequestApiFactory
     */
    public static function getRequestFactory(PayloadReaderInterface $mapper)
    {
        return new RequestApiFactory($mapper);
    }

    /**
     * @param PayloadReaderInterface $mapper
     * @return ResponseApiFactory
     */
    public static function getResponseFactory(PayloadReaderInterface $mapper)
    {
        return new ResponseApiFactory($mapper);
    }

    /**
     * @param PayloadReaderInterface $mapper
     * @return ActionApiFactory
     */
    public static function getActionFactory(PayloadReaderInterface $mapper)
    {
        return new ActionApiFactory($mapper);
    }

    /**
     * @param PayloadReaderInterface $mapper
     */
    final public function __construct(PayloadReaderInterface $mapper)
    {
        $this->mapper = $mapper;
    }

    /**
     * Build an Api class given a command input.
     *
     * Will use the given read Mapper to translate from the command data to
     * an Api instance.
     *
     * The CliInput provides general information about the component that was
     * defined when the script was executed.
     *
     * @param array $command
     * @param CliInput $input
     * @return Api
     */
    abstract public function build(array $command, CliInput $input);
}
