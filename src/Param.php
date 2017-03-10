<?php
/**
 * PHP 5 SDK for the KATANA(tm) Framework (http://katana.kusanagi.io)
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

interface Param
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
     * Return the name of the parameter.
     *
     * @return string
     */
    public function getName();

    /**
     * Return the value of the Param.
     *
     * @return mixed
     */
    public function getValue();

    /**
     * Return the type of the Param.
     *
     * @return string
     */
    public function getType();

    /**
     * Determine if the Param exists in the request.
     *
     * @return bool
     */
    public function exists();

    /**
     * Return a copy of the Param with the given name.
     *
     * @param string $name
     * @return self
     */
    public function copyWithName($name);

    /**
     * Return a copy of the Param with the given value.
     *
     * @param mixed $value
     * @return self
     */
    public function copyWithValue($value);

    /**
     * Return a copy of the Param with the given type.
     *
     * @param string $type
     * @return self
     */
    public function copyWithType($type);
}
