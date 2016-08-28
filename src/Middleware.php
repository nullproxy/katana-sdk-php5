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

namespace Katana\Sdk;

use Katana\Sdk\Api\Factory\ApiFactory;
use Katana\Sdk\Api\Mapper\CompactPayloadMapper;
use Katana\Sdk\Component\AbstractComponent;

/**
 * Middleware class that can run requests and responses
 *
 * @package Katana\Sdk
 */
class Middleware extends AbstractComponent
{
    /**
     * @param callable $callable
     */
    public function runRequest(callable $callable)
    {
        $apiFactory = ApiFactory::getRequestFactory(new CompactPayloadMapper());
        $this->executor->execute($apiFactory, $this->input, $callable);
    }

    /**
     * @param callable $callable
     */
    public function runResponse(callable $callable)
    {
        $apiFactory = ApiFactory::getResponseFactory(new CompactPayloadMapper());
        $this->executor->execute($apiFactory, $this->input, $callable);
    }
}
