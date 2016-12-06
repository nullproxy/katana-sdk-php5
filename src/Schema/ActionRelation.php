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

class ActionRelation
{
    /**
     * @var string
     */
    private $service = '';

    /**
     * @var string
     */
    private $version = '';

    /**
     * @var string
     */
    private $action = '';

    /**
     * @var string
     */
    private $type = 'one';

    /**
     * @var bool
     */
    private $validate = false;

    /**
     * @param string $service
     * @param string $version
     * @param string $action
     * @param string $type
     * @param bool $validate
     */
    public function __construct(
        $service,
        $version,
        $action,
        $type = 'one',
        $validate = false
    ) {
        $this->service = $service;
        $this->version = $version;
        $this->action = $action;
        $this->type = $type;
        $this->validate = $validate;
    }

    /**
     * @return string
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
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
    public function isValidate()
    {
        return $this->validate;
    }
}
