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

use Katana\Sdk\Schema\ServiceSchema;

interface ApiInterface
{
    /**
     * @return string
     */
    public function getPath();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getVersion();

    /**
     * @return string
     */
    public function getPlatformVersion();

    /**
     * @return array
     */
    public function getVariables();

    /**
     * @param $name
     * @return string
     */
    public function getVariable($name);

    /**
     * @return boolean
     */
    public function isDebug();

    /**
     * @param string $name
     * @return boolean
     */
    public function hasResource($name);

    /**
     * @param string $name
     * @return mixed
     */
    public function getResource($name);

    /**
     * @param $value
     * @return boolean
     */
    public function log($value);

    /**
     * @param string $name
     * @param string $version
     * @return ServiceSchema
     */
    public function getServiceSchema($name, $version);
}
