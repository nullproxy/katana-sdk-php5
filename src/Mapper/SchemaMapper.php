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

namespace Katana\Sdk\Mapper;

use Katana\Sdk\Schema\ActionEntity;
use Katana\Sdk\Schema\ActionSchema;
use Katana\Sdk\Schema\Protocol\HttpActionSchema;
use Katana\Sdk\Schema\Protocol\HttpServiceSchema;
use Katana\Sdk\Schema\ServiceSchema;

class SchemaMapper
{
    private function read(array $source, $path, $default = null)
    {
        if (strpos($path, '.') !== false) {
            list($key, $rest) = explode('.', $path, 2);

            if (isset($source[$key])) {
                return $this->read($source[$key], $rest, $default);
            } else {
                return $default;
            }
        }

        $key = $path;
        if (isset($source[$key])) {
            return $source[$key];
        } else {
            return $default;
        }


    }
    
    /**
     * @param string $name
     * @param string $version
     * @param array $raw
     * @return ServiceSchema
     */
    public function getServiceSchema($name, $version, array $raw)
    {
        $http = new HttpServiceSchema(
            $this->read($raw, 'h.g', true),
            $this->read($raw, 'h.b', '')
        );

        $actions = [];
        foreach ($this->read($raw, 'ac', []) as $actionName => $action) {
            $actions[] = new ActionSchema(
                $actionName,
                new ActionEntity(
                    $this->read($action, 'e', ''),
                    $this->read($action, 'd', '/'),
                    $this->read($action, 'k', 'id'),
                    $this->read($action, 'c', false)
                ),
                new HttpActionSchema(
                    $this->read($action, 'h.g', true),
                    $this->read($action, 'h.p', '/'),
                    $this->read($action, 'h.m', 'get'),
                    $this->read($action, 'h.i', 'query'),
                    $this->read($action, 'h.b', 'text/plain')
                ),
                $this->read($action, 'D', false)
            );
        }

        return new ServiceSchema($name, $version, $http, $actions);
    }
}
