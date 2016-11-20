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
use Katana\Sdk\Api\Factory\ServiceApiFactory;
use Katana\Sdk\Api\Mapper\CompactPayloadMapper;
use Katana\Sdk\Api\Mapper\PayloadMapperInterface;
use Katana\Sdk\Component\AbstractComponent;

/**
 * Service class that can run actions
 *
 * @package Katana\Sdk
 */
class Service extends AbstractComponent
{
    /**
     * @param string $name
     * @param callable $callback
     * @return $this
     */
    public function action($name, callable $callback)
    {
        $this->setCallback($name, $callback);

        return $this;
    }

    /**
     * @param PayloadMapperInterface $mapper
     * @return ServiceApiFactory
     */
    protected function getApiFactory(PayloadMapperInterface $mapper)
    {
        return ApiFactory::getServiceFactory($this, $mapper, $this->logger);
    }
}
