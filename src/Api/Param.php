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
 * Api class that encapsulates an input Parameter.
 *
 * @package Katana\Sdk\Api
 */
class Param
{
    const TYPE_NULL = 'null';
    const TYPE_BOOLEAN = 'boolean';
    const TYPE_INTEGER = 'integer';
    const TYPE_FLOAT = 'float';
    const TYPE_ARRAY = 'array';
    const TYPE_OBJECT = 'object';
    const TYPE_STRING = 'string';

    const TYPE_CLASSES = [
        self::TYPE_NULL,
        self::TYPE_BOOLEAN,
        self::TYPE_INTEGER,
        self::TYPE_FLOAT,
        self::TYPE_ARRAY,
        self::TYPE_OBJECT,
        self::TYPE_STRING,
    ];

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $value;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var boolean
     */
    protected $exists;

    /**
     * @param string $value
     * @param string $type
     * @return array|bool|float|int|null|object
     */
    private function cast($value, $type)
    {
        switch ($type) {
            case self::TYPE_NULL:
                return null;
            case self::TYPE_BOOLEAN:
                return (bool) $value;
            case self::TYPE_INTEGER:
                return (int) $value;
            case self::TYPE_FLOAT:
                return (float) $value;
            case self::TYPE_ARRAY:
                return (array) $value;
            case self::TYPE_OBJECT:
                return (object) $value;
            case self::TYPE_STRING:
                return $value;
        }

        return $value;
    }

    /**
     * @param string $name
     * @param string $value
     * @param string $type
     * @param bool $exists
     */
    public function __construct(
        $name,
        $value = '',
        $type = self::TYPE_STRING,
        $exists = false
    ) {
        if (!in_array($type, self::TYPE_CLASSES)) {
            $type = self::TYPE_STRING;
        }

        $this->name = $name;
        $this->value = $value;
        $this->type = $type;
        $this->exists = $exists;
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
    public function getValue()
    {
        return $this->cast($this->value, $this->type);
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return boolean
     */
    public function exists()
    {
        return $this->exists;
    }

    /**
     * @param string $name
     * @return Param
     */
    public function copyWithName($name)
    {
        return new static(
            $name,
            $this->value,
            $this->type,
            $this->exists
        );
    }

    /**
     * @param string $type
     * @return Param
     */
    public function copyWithType($type)
    {
        return new static(
            $this->name,
            $this->value,
            $type,
            $this->exists
        );
    }

    /**
     * @param string $value
     * @return Param
     */
    public function copyWithValue($value)
    {
        return new static(
            $this->name,
            $value,
            $this->type,
            $this->exists
        );
    }
}
