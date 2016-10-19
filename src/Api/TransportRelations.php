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
 * Support Transport Api class that encapsulates a list of relations.
 * @package Katana\Sdk\Api
 */
class TransportRelations
{
    /**
     * @var array
     */
    private $relations = [];

    /**
     * @param array $relations
     */
    public function __construct(array $relations = [])
    {
        $this->relations = $relations;
    }

    /**
     * @param string $service
     * @return array
     */
    public function get($service = '')
    {
        $relations = $this->relations;
        if ($service) {
            $relations = isset($relations[$service])? $relations[$service] : [];
        }

        return $relations;
    }

    /**
     * @param string $serviceFrom
     * @param string $idFrom
     * @param string $serviceTo
     * @param string $idTo
     */
    public function addSimple($serviceFrom, $idFrom, $serviceTo, $idTo)
    {
        $this->relations[$serviceFrom][$idFrom][$serviceTo] = $idTo;
    }

    /**
     * @param string $serviceFrom
     * @param string $idFrom
     * @param string $serviceTo
     * @param array $idsTo
     */
    public function addMultipleRelation($serviceFrom, $idFrom, $serviceTo, array $idsTo)
    {
        $this->relations[$serviceFrom][$idFrom][$serviceTo] = $idsTo;
    }
}
