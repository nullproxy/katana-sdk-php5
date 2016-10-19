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
 * Support Transport Api class that encapsulates a list of errors.
 * @package Katana\Sdk\Api
 */
class TransportErrors
{
    /**
     * @var Error[]
     */
    private $errors = [];

    /**
     * @param Error[] $errors
     */
    public function __construct($errors = [])
    {
        $this->errors = $errors;
    }

    /**
     * @param Error $error
     */
    public function add(Error $error)
    {
        $this->errors[] = $error;
    }

    /**
     * @param string $service
     * @return Error[]
     */
    public function get($service = '')
    {
        $errors = $this->errors;
        if ($service) {
            $errors = isset($errors[$service])? $errors[$service] : [];
        }

        return $errors;
    }

    /**
     * @param string $service
     * @return array
     */
    public function getArray($service = '')
    {
        $errors = [];
        foreach ($this->errors as $error) {
            if ($service && $error->getService() !== $service) {
                continue;
            }

            $errorOutput = [
                'm' => $error->getMessage(),
                'c' => $error->getCode(),
                's' => $error->getStatus(),
            ];

            if ($service) {
                $errors[$error->getVersion()][] = $errorOutput;
            } else {
                $errors[$error->getService()][$error->getVersion()][] = $errorOutput;
            }
        }

        return $errors;
    }
}
