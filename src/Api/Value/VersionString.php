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

namespace Katana\Sdk\Api\Value;

use Katana\Sdk\Exception\InvalidValueException;

class VersionString
{
    /**
     * @var string
     */
    private $version;

    /**
     * @param string $version
     * @throws InvalidValueException
     */
    public function __construct($version)
    {
        if (preg_match('/[^a-zA-Z0-9*.,_-]/', $version)) {
            throw new InvalidValueException("Invalid version: $version");
        }

        $this->version = $version;
    }

    /**
     * @return mixed
     */
    public function getVersion()
    {
        return $this->version;
    }
}
