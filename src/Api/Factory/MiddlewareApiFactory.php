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
use Katana\Sdk\Api\RequestApi;
use Katana\Sdk\Api\ResponseApi;
use Katana\Sdk\Console\CliInput;

/**
 * @package Katana\Sdk\Api\Factory
 */
class MiddlewareApiFactory extends ApiFactory
{
    /**
     * Build a Request Api class instance
     *
     * @param string $action
     * @param array $data
     * @param CliInput $input
     * @return Api
     */
    public function build($action, array $data, CliInput $input)
    {
        if ($action === 'request') {
            return new RequestApi(
                $this->logger,
                $this->component,
                dirname(realpath($_SERVER['SCRIPT_FILENAME'])),
                $input->getName(),
                $input->getVersion(),
                $input->getPlatformVersion(),
                $input->getVariables(),
                $input->isDebug(),
                $this->mapper->getHttpRequest($data),
                $this->mapper->getServiceCall($data)
            );

        } elseif($action === 'response') {
            return new ResponseApi(
                $this->logger,
                $this->component,
                dirname(realpath($_SERVER['SCRIPT_FILENAME'])),
                $input->getName(),
                $input->getVersion(),
                $input->getPlatformVersion(),
                $input->getVariables(),
                $input->isDebug(),
                $this->mapper->getHttpRequest($data),
                $this->mapper->getHttpResponse($data),
                $this->mapper->getTransport($data)
            );
        }

    }
}
