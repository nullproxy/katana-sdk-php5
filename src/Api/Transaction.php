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

namespace Katana\Sdk\Api;

/**
 * Support Api class that encapsulates a Transaction.
 *
 * @package Katana\Sdk\Api
 */
class Transaction
{
    /**
     * @var ServiceOrigin
     */
    private $origin;

    /**
     * @var string
     */
    private $action = '';

    /**
     * @var Param[]
     */
    private $params = [];

    /**
     * @param ServiceOrigin $origin
     * @param string $action
     * @param Param[] $params
     */
    public function __construct(
        ServiceOrigin $origin,
        $action,
        array $params = []
    ) {
        $this->origin = $origin;
        $this->action = $action;
        $this->params = $params;
    }

    /**
     * @return ServiceOrigin
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return Param[]
     */
    public function getParams()
    {
        return $this->params;
    }
}
