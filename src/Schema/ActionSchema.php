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
     * @param string $name
     * @param ActionEntity $entity
     * @param HttpActionSchema $http
     * @param bool $deprecated
     */
    public function __construct(
        $name,
        ActionEntity $entity,
        HttpActionSchema $http,
        $deprecated
    ) {
        $this->name = $name;
        $this->entity = $entity;
        $this->http = $http;
        $this->deprecated = $deprecated;
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

    public function getParams()
    {
        
    }

    public function hasParam($name)
    {
        
    }

    public function getParamSchema($name)
    {

    }

    public function getFiles()
    {

    }

    public function hasFile($name)
    {

    }

    public function getFileSchema($name)
    {

    }

    /**
     * @return HttpActionSchema
     */
    public function getHttpSchema()
    {
        return $this->http;
    }
}
