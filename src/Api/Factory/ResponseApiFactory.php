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

use Katana\Sdk\Api\ResponseApi;
use Katana\Sdk\Console\CliInput;

/**
 * @package Katana\Sdk\Api\Factory
 */
class ResponseApiFactory extends ApiFactory
{
    /**
     * Build a Response Api class instance
     *
     * @param array $data
     * @param CliInput $input
     * @return ResponseApi
     */
    public function build(array $data, CliInput $input)
    {
        return new ResponseApi(
            dirname(realpath($_SERVER['SCRIPT_FILENAME'])),
            $input->getName(),
            $input->getVersion(),
            $input->getPlatformVersion(),
            $input->getVariables(),
            $input->isDebug(),
            $this->mapper->getHttpResponse($data),
            $this->mapper->getTransport($data)
        );
    }
}
