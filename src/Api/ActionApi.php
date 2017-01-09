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
use Katana\Sdk\Component\Component;
use Katana\Sdk\Exception\InvalidValueException;
use Katana\Sdk\Exception\SchemaException;
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
     * @param Component $component
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
        Component $component,
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
     * @return Action
     */
    public function setProperty($name, $value)
    {
        $this->transport->getMeta()->setProperty($name, $value);

        return $this;
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
        return new File($name, $path, $mime);
    }

    /**
     * @param File $file
     * @return Action
     * @throws InvalidValueException
     * @throws SchemaException
     */
    public function setDownload(File $file)
    {
        $service = $this->getServiceSchema($this->name, $this->version);

        if ($file->isLocal() && !$service->hasFileServer()) {
            throw new InvalidValueException(sprintf(
                'File server not configured: "%s" (%s)',
                $this->name,
                $this->version
            ));
        }

        $this->transport->setBody($file);

        return $this;
    }

    /**
     * @param array $entity
     * @return Action
     * @throws TransportException
     */
    public function setEntity(array $entity)
    {
        if (count(preg_grep('/^\d+$/', array_keys($entity)))) {
            throw new TransportException('Unexpected collection');
        }

        $this->transport->setData($this->name, $this->version, $this->actionName, $entity);

        return $this;
    }

    /**
     * @param array $collection
     * @return Action
     * @throws TransportException
     */
    public function setCollection(array $collection)
    {
        if (count(preg_grep('/^\d+$/', array_keys($collection))) < count($collection)) {
            throw new TransportException('Unexpected entity');
        }

        $this->transport->setCollection($this->name, $this->version, $this->actionName, $collection);

        return $this;
    }

    /**
     * @param string $primaryKey
     * @param string $service
     * @param string $foreignKey
     * @return Action
     */
    public function relateOne($primaryKey, $service, $foreignKey)
    {
        $this->transport->addSimpleRelation($this->name, $primaryKey, $service, $foreignKey);

        return $this;
    }

    /**
     * @param string $primaryKey
     * @param string $service
     * @param array $foreignKeys
     * @return Action
     */
    public function relateMany($primaryKey, $service, array $foreignKeys)
    {
        $this->transport->addMultipleRelation($this->name, $primaryKey, $service, $foreignKeys);

        return $this;
    }

    /**
     * @param string $link
     * @param string $uri
     * @return Action
     */
    public function setLink($link, $uri)
    {
        $this->transport->setLink($this->name, $link, $uri);

        return $this;
    }

    /**
     * @param string $action
     * @param array $params
     * @return Action
     */
    public function commit($action, $params = [])
    {
        $address = $this->transport->getMeta()->getGateway()[1];
        $this->transport->addTransaction(
            new Transaction(
                'commit',
                new ServiceOrigin($address, $this->name, $this->version),
                $action,
                $params
            )
        );

        return $this;
    }

    /**
     * @param string $action
     * @param array $params
     * @return Action
     */
    public function rollback($action, $params = [])
    {
        $address = $this->transport->getMeta()->getGateway()[1];
        $this->transport->addTransaction(
            new Transaction(
                'rollback',
                new ServiceOrigin($address, $this->name, $this->version),
                $action,
                $params
            )
        );

        return $this;
    }

    /**
     * @param string $action
     * @param array $params
     * @return Action
     */
    public function complete($action, $params = [])
    {
        $address = $this->transport->getMeta()->getGateway()[1];
        $this->transport->addTransaction(
            new Transaction(
                'complete',
                new ServiceOrigin($address, $this->name, $this->version),
                $action,
                $params
            )
        );

        return $this;
    }

    /**
     * @param string $service
     * @param string $version
     * @param string $action
     * @param Param[] $params
     * @param File[] $files
     * @return Action
     * @throws InvalidValueException
     */
    public function call(
        $service,
        $version,
        $action,
        array $params = [],
        array $files = []
    ) {
        $versionString = new VersionString($version);
        $address = $this->transport->getMeta()->getGateway()[1];
        $this->transport->addCall(new Call(
            new ServiceOrigin($address, $this->name, $this->version),
            $service,
            $versionString,
            $action,
            $params
        ));

        $service = $this->getServiceSchema($this->name, $this->version);

        foreach ($files as $file) {
            if ($file->isLocal() && !$service->hasFileServer()) {
                throw new InvalidValueException(sprintf(
                    'File server not configured: "%s" (%s)',
                    $this->name,
                    $this->version
                ));
            }

            $this->transport->addFile($service, $versionString, $action, $file);
        }

        return $this;
    }

    /**
     * @param string $message
     * @param int $code
     * @param string $status
     * @return Action
     */
    public function error($message, $code = 0, $status = '')
    {
        $this->transport->addError(new Error($this->name, $this->version, $message, $code, $status));

        return $this;
    }
}
