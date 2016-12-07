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

use Katana\Sdk\Action;
use Katana\Sdk\Api\Value\VersionString;
use Katana\Sdk\Component\AbstractComponent;
use Katana\Sdk\Exception\TransportException;
use Katana\Sdk\Logger\KatanaLogger;
use Katana\Sdk\Schema\Mapping;

class ActionApi extends Api implements Action
{
    use ParamAccessorTrait;

    /**
     * @var string
     */
    private $actionName;

    /**
     * @var Transport
     */
    private $transport;

    /**
     * Action constructor.
     * @param KatanaLogger $logger
     * @param AbstractComponent $component
     * @param Mapping $mapping
     * @param string $path
     * @param string $name
     * @param string $version
     * @param string $platformVersion
     * @param array $variables
     * @param bool $debug
     * @param string $actionName
     * @param Transport $transport
     * @param Param[] $params
     */
    public function __construct(
        KatanaLogger $logger,
        AbstractComponent $component,
        Mapping $mapping,
        $path,
        $name,
        $version,
        $platformVersion,
        array $variables,
        $debug,
        $actionName,
        Transport $transport,
        array $params = []
    ) {
        parent::__construct(
            $logger,
            $component,
            $mapping,
            $path,
            $name,
            $version,
            $platformVersion,
            $variables,
            $debug
        );

        $this->actionName = $actionName;
        $this->transport = $transport;
        $this->params = $this->prepareParams($params);
    }

    /**
     * @return Transport
     */
    public function getTransport()
    {
        return $this->transport;
    }

    /**
     * @return bool
     */
    public function isOrigin()
    {
        return $this->transport->getMeta()->getOrigin() === $this->name;
    }

    /**
     * @return string
     */
    public function getActionName()
    {
        return $this->actionName;
    }

    /**
     * @param string $name
     * @param string $value
     * @return bool
     */
    public function setProperty($name, $value)
    {
        return $this->transport->getMeta()->setProperty($name, $value);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasFile($name)
    {
        return $this->transport->hasFile($this->name, $this->version, $this->actionName, $name);
    }

    /**
     * @param string $name
     * @return File
     */
    public function getFile($name)
    {
        if ($this->transport->hasFile($this->name, $this->version, $this->actionName, $name)) {
            return $this->transport->getFile($this->name, $this->version, $this->actionName, $name);
        } else {
            return $this->newFile($name, '');
        }
    }

    /**
     * @return File[]
     */
    public function getFiles()
    {
        $files = [];

        foreach ($this->transport->getFiles()->getAll() as $serviceFiles) {
            foreach ($serviceFiles as $versionFiles) {
                foreach ($versionFiles as $actionFiles) {
                    $files = array_merge($files, array_values($actionFiles));
                }
            }
        }

        return $files;
    }

    /**
     * @param string $name
     * @param string $path
     * @param string $mime
     * @return File
     */
    public function newFile($name, $path, $mime = '')
    {
        if (strpos($path, 'file://') !== 0) {
            $path = "file://$path";
        }

        return new File($name, $path, $mime);
    }

    /**
     * @param File $file
     * @return bool
     */
    public function setDownload(File $file)
    {
        return $this->transport->setBody($file);
    }

    /**
     * @param array $entity
     * @return bool
     * @throws TransportException
     */
    public function setEntity(array $entity)
    {
        if (count(preg_grep('/^\d+$/', array_keys($entity)))) {
            throw new TransportException('Unexpected collection');
        }

        return $this->transport->setData($this->name, $this->version, $this->actionName, $entity);
    }

    /**
     * @param array $collection
     * @return bool
     * @throws TransportException
     */
    public function setCollection(array $collection)
    {
        if (count(preg_grep('/^\d+$/', array_keys($collection))) < count($collection)) {
            throw new TransportException('Unexpected entity');
        }

        return $this->transport->setCollection($this->name, $this->version, $this->actionName, $collection);
    }

    /**
     * @param string $primaryKey
     * @param string $service
     * @param string $foreignKey
     * @return bool
     */
    public function relateOne($primaryKey, $service, $foreignKey)
    {
        return $this->transport->addSimpleRelation($this->name, $primaryKey, $service, $foreignKey);
    }

    /**
     * @param string $primaryKey
     * @param string $service
     * @param array $foreignKeys
     * @return bool
     */
    public function relateMany($primaryKey, $service, array $foreignKeys)
    {
        return $this->transport->addMultipleRelation($this->name, $primaryKey, $service, $foreignKeys);
    }

    /**
     * @param string $link
     * @param string $uri
     * @return bool
     */
    public function setLink($link, $uri)
    {
        return $this->transport->setLink($this->name, $link, $uri);
    }

    /**
     * @param string $action
     * @param array $params
     * @return boolean
     */
    public function commit($action, $params = [])
    {
        return $this->transport->addTransaction(
            new Transaction(
                'commit',
                new ServiceOrigin($this->name, $this->version),
                $action,
                $params
            )
        );
    }

    /**
     * @param string $action
     * @param array $params
     * @return boolean
     */
    public function rollback($action, $params = [])
    {
        return $this->transport->addTransaction(
            new Transaction(
                'rollback',
                new ServiceOrigin($this->name, $this->version),
                $action,
                $params
            )
        );
    }

    /**
     * @param string $action
     * @param array $params
     * @return boolean
     */
    public function complete($action, $params = [])
    {
        return $this->transport->addTransaction(
            new Transaction(
                'complete',
                new ServiceOrigin($this->name, $this->version),
                $action,
                $params
            )
        );
    }

    /**
     * @param string $service
     * @param string $version
     * @param string $action
     * @param Param[] $params
     * @param File[] $files
     * @return bool
     */
    public function call(
        $service,
        $version,
        $action,
        array $params = [],
        array $files = []
    ) {
        $versionString = new VersionString($version);
        $result = $this->transport->addCall(new Call(
            new ServiceOrigin($this->name, $this->version),
            $service,
            $versionString,
            $action,
            $params
        ));

        foreach ($files as $file) {
            $this->transport->addFile($service, $versionString, $action, $file);
        }

        return $result;
    }

    /**
     * @param string $message
     * @param int $code
     * @param string $status
     * @return bool
     */
    public function error($message, $code = 0, $status = '')
    {
        return $this->transport->addError(new Error($this->name, $this->version, $message, $code, $status));
    }
}
