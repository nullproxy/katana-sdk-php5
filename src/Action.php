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

use Katana\Sdk\Api\File;
use Katana\Sdk\Api\Param;
use Katana\Sdk\Exception\TransportException;

interface Action
{
    /**
     * @return bool
     */
    public function isOrigin();

    /**
     * @return string
     */
    public function getActionName();

    /**
     * @param string $name
     * @param string $value
     */
    public function setProperty($name, $value);

    /**
     * @param string $location
     * @param string $name
     * @return Param
     */
    public function getParam($location, $name);

    /**
     * @param string $location
     * @param string $name
     * @param string $value
     * @param string $type
     * @return Param
     */
    public function newParam(
        $location,
        $name,
        $value = '',
        $type = Param::TYPE_STRING
    );

    /**
     * @param string $name
     * @return bool
     */
    public function hasFile($name);

    /**
     * @param string $name
     * @return File
     */
    public function getFile($name);

    /**
     * @param string $name
     * @param string $path
     * @param string $mime
     * @return File
     */
    public function newFile($name, $path, $mime = '');

    /**
     * @param File $file
     */
    public function setDownload(File $file);

    /**
     * @param array $entity
     * @throws TransportException
     */
    public function setEntity(array $entity);

    /**
     * @param array $collection
     * @throws TransportException
     */
    public function setCollection(array $collection);

    /**
     * @param string $primaryKey
     * @param string $service
     * @param string $foreignKey
     */
    public function relateOne($primaryKey, $service, $foreignKey);

    /**
     * @param string $primaryKey
     * @param string $service
     * @param array $foreignKeys
     */
    public function relateMany($primaryKey, $service, array $foreignKeys);

    /**
     * @param string $link
     * @param string $uri
     */
    public function setLink($link, $uri);

    /**
     * @param string $action
     * @param array $params
     */
    public function transaction($action, $params = []);

    /**
     * @param $service
     * @param $version
     * @param $action
     * @param Param[] $params
     */
    public function call($service, $version, $action, array $params = []);

    /**
     * @param string $message
     * @param int $code
     * @param string $status
     */
    public function error($message, $code = 0, $status = '');
}
