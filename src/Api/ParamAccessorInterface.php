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

interface ParamAccessorInterface
{
    /**
     * @param string $name
     * @return bool
     */
    public function hasParam($name);

    /**
     * @param string $name
     * @return Param
     */
    public function getParam($name);

    /**
     * @return Param[]
     */
    public function getParams();

    /**
     * @param string $name
     * @param string $value
     * @param string $type
     * @return Param
     */
    public function newParam($name, $value = '', $type = Param::TYPE_STRING);
}
