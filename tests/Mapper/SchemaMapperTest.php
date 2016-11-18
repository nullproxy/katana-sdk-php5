<?php
use Katana\Sdk\Exception\SchemaException;
use Katana\Sdk\Mapper\SchemaMapper;
use Katana\Sdk\Schema\Mapping;
use Katana\Sdk\Schema\ServiceSchema;

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

class SchemaMapperTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mapping
     */
    private $mapping;

    /**
     * @var SchemaMapper
     */
    private $mapper;

    public function setUp()
    {
        $this->mapping = new Mapping();
        $this->mapper = new SchemaMapper();
        $map = json_decode(
            file_get_contents(__DIR__ . '/service_mapping.json'),
            true
        );

        $services = [];
        foreach ($map as $service => $serviceMap) {
            foreach ($serviceMap as $version => $schema) {
                $services[] = $this->mapper->getServiceSchema($service, $version, $schema);
            }
        }
        $this->mapping->load($services);
    }

    public function testServiceNotFound()
    {
        $this->expectException(SchemaException::class);
        $this->expectExceptionMessage('Cannot resolve schema for Service: comments (1.0.0)');
        $this->mapping->find('comments', '1.0.0');
    }

    public function testVersionNotFound()
    {
        $this->expectException(SchemaException::class);
        $this->expectExceptionMessage('Cannot resolve schema for Service: posts (0.1.0)');
        $this->mapping->find('posts', '0.1.0');
    }

    public function testServiceMapping()
    {
        $service = $this->mapping->find('posts', '1.0.0');
        $this->assertEquals('posts', $service->getName());
        $this->assertEquals('1.0.0', $service->getVersion());

        $this->assertCount(1, $service->getActions());
        $this->assertTrue($service->hasAction('list'));
        $this->assertFalse($service->hasAction('create'));

        $this->assertTrue($service->getHttpSchema()->isAccessible());
        $this->assertEquals('/1.0.0', $service->getHttpSchema()->getBasePath());
    }

    public function testActionNotFound()
    {
        $service = $this->mapping->find('posts', '1.0.0');
        $this->expectException(SchemaException::class);
        $this->expectExceptionMessage('Cannot resolve schema for action: foo');
        $service->getActionSchema('foo');
    }

    public function testActionMapping()
    {
        $service = $this->mapping->find('posts', '1.0.0');
        $action = $service->getActionSchema('list');

        $this->assertEquals('list', $action->getName());
        $this->assertEquals(false, $action->isDeprecated());

        // Assert entity
        $this->assertEquals('entity:data', $action->getEntityPath());
        $this->assertEquals(':', $action->getPathDelimiter());
        $this->assertEquals('uid', $action->getPrimaryKey());
        $this->assertEquals(true, $action->isCollection());

        // Assert http
        $http = $action->getHttpSchema();
        $this->assertEquals(true, $http->isAccessible());
        $this->assertEquals('/posts/{user_id}', $http->getPath());
        $this->assertEquals('get', $http->getMethod());
        $this->assertEquals('query', $http->getInput());
        $this->assertEquals('text/plain', $http->getBody());
    }
}
