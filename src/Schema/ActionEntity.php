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

class ActionEntity
{
    /**
     * Path to the entity in each object returned by the action.
     *
     * @var string
     */
    private $entityPath = '';

    /**
     * Delimiter used to specify the traversal between objects.
     *
     * @var string
     */
    private $pathDelimiter = '/';

    /**
     * Name of the property in the entity object which defines the primary key.
     *
     * @var string
     */
    private $primaryKey = 'id';

    /**
     * Determines if the action returns a collection of entities.
     *
     * @var bool
     */
    private $collection = false;

    /**
     * @param string $entityPath
     * @param string $pathDelimiter
     * @param string $primaryKey
     * @param bool $collection
     */
    public function __construct($entityPath, $pathDelimiter, $primaryKey, $collection)
    {
        $this->entityPath = $entityPath;
        $this->pathDelimiter = $pathDelimiter;
        $this->primaryKey = $primaryKey;
        $this->collection = $collection;
    }

    /**
     * @return string
     */
    public function getEntityPath()
    {
        return $this->entityPath;
    }

    /**
     * @return string
     */
    public function getPathDelimiter()
    {
        return $this->pathDelimiter;
    }

    /**
     * @return string
     */
    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }

    /**
     * @return boolean
     */
    public function isCollection()
    {
        return $this->collection;
    }
}
