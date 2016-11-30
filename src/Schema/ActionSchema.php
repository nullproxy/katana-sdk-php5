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

namespace Katana\Sdk\Schema;

use Katana\Sdk\Exception\SchemaException;
use Katana\Sdk\Schema\Protocol\HttpActionSchema;

class ActionSchema
{
    /**
     * @var string
     */
    private $name = '';

    /**
     * @var ActionEntity
     */
    private $entity;

    /**
     * @var HttpActionSchema
     */
    private $http;

    /**
     * @var bool
     */
    private $deprecated = false;

    /**
     * @var ParamSchema[]
     */
    private $params = [];

    /**
     * @var FileSchema[]
     */
    private $files = [];

    /**
     * @param string $name
     * @param ActionEntity $entity
     * @param HttpActionSchema $http
     * @param bool $deprecated
     * @param ParamSchema[] $params
     * @param FileSchema[] $files
     */
    public function __construct(
        $name,
        ActionEntity $entity,
        HttpActionSchema $http,
        $deprecated,
        array $params,
        array $files
    ) {
        $paramNames = array_map(function (ParamSchema $param) {
            return $param->getName();
        }, $params);

        $fileNames = array_map(function (FileSchema $file) {
            return $file->getName();
        }, $files);

        $this->name = $name;
        $this->entity = $entity;
        $this->http = $http;
        $this->deprecated = $deprecated;
        $this->params = $params;
        $this->params = array_combine($paramNames, $params);
        $this->files = array_combine($fileNames, $files);
    }

    /**
     * @return bool
     */
    public function isDeprecated()
    {
        return $this->deprecated;
    }

    /**
     * @return bool
     */
    public function isCollection()
    {
        return $this->entity->isCollection();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getEntityPath()
    {
        return $this->entity->getEntityPath();
    }

    /**
     * @return string
     */
    public function getPathDelimiter()
    {
        return $this->entity->getPathDelimiter();
    }

    /**
     * @return string
     */
    public function getPrimaryKey()
    {
        return $this->entity->getPrimaryKey();
    }

    public function resolveEntity(array $entity)
    {
        
    }

    public function hasEntity()
    {

    }

    public function getEntity()
    {

    }

    public function hasRelations()
    {

    }

    public function getRelations()
    {

    }

    /**
     * @return ParamSchema[]
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasParam($name)
    {
        return isset($this->params[$name]);
    }

    /**
     * @param string $name
     * @return ParamSchema
     * @throws SchemaException
     */
    public function getParamSchema($name)
    {
        if (!$this->hasParam($name)) {
            throw new SchemaException("Cannot resolve schema for parameter: $name");
        }

        return $this->params[$name];
    }

    /**
     * @return FileSchema[]
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasFile($name)
    {
        return isset($this->files[$name]);
    }

    /**
     * @param string $name
     * @return FileSchema
     * @throws SchemaException
     */
    public function getFileSchema($name)
    {
        if (!$this->hasFile($name)) {
            throw new SchemaException("Cannot resolve schema for file parameter: $name");
        }

        return $this->files[$name];
    }

    /**
     * @return HttpActionSchema
     */
    public function getHttpSchema()
    {
        return $this->http;
    }
}
