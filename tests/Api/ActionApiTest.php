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

namespace Katana\Sdk\Tests\Api;

use Katana\Sdk\Api\ActionApi;
use Katana\Sdk\Api\DeferCall;
use Katana\Sdk\Api\File;
use Katana\Sdk\Api\Transport;
use Katana\Sdk\Api\TransportMeta;
use Katana\Sdk\Component\Component;
use Katana\Sdk\Exception\InvalidValueException;
use Katana\Sdk\Logger\KatanaLogger;
use Katana\Sdk\Schema\Mapping;
use Katana\Sdk\Schema\ServiceSchema;
use Prophecy\Argument;

class ActionApiTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ActionApi
     */
    private $action;

    /**
     * @var KatanaLogger
     */
    private $logger;

    /**
     * @var Transport
     */
    private $transport;

    /**
     * @var ServiceSchema
     */
    private $serviceSchema;

    public function setUp()
    {
        $this->logger = $this->prophesize(KatanaLogger::class);
        $this->logger->getLevel()->willReturn(KatanaLogger::LOG_DEBUG);

        $this->service = $this->prophesize(ServiceSchema::class);

        $mapping = $this->prophesize(Mapping::class);
        $mapping->find(
            Argument::any(), Argument::any()
        )->willReturn($this->service->reveal());

        $meta = $this->prophesize(TransportMeta::class);
        $meta->getGateway()->willReturn('127.0.0.1:80');

        $this->transport = $this->prophesize(Transport::class);
        $this->transport->getMeta()->willReturn($meta);

        $this->action = new ActionApi(
            $this->logger->reveal(),
            $this->prophesize(Component::class)->reveal(),
            $mapping->reveal(),
            '/',
            'test',
            '1.0',
            '1.0.0',
            [],
            true,
            'action',
            $this->transport->reveal()
        );
    }

    public function testNewFile()
    {
        $file = $this->action->newFile('file', __DIR__ . '/file.txt');
        $this->assertInstanceOf(File::class, $file);
    }

    public function testSetDownload()
    {
        $this->service->hasFileServer()->willReturn(true);

        /** @var File $file */
        $file = $this->prophesize(File::class)->reveal();

        $this->transport->setBody($file)->shouldBeCalled();
        $this->action->setDownload($file);
    }

    public function testSetLocalFileWithoutServer()
    {
        $this->service->hasFileServer()->willReturn(false);

        /** @var File $file */
        $file = $this->prophesize(File::class);
        $file->isLocal()->willReturn(true);

        $this->expectException(InvalidValueException::class);
        $this->expectExceptionMessage('File server not configured: "test" (1.0)');
        $this->action->setDownload($file->reveal());
    }

    public function testSetRemoteFileWithoutServer()
    {
        $this->service->hasFileServer()->willReturn(false);

        /** @var File $file */
        $file = $this->prophesize(File::class);
        $file->isLocal()->willReturn(false);
        $file = $file->reveal();

        $this->transport->setBody($file)->shouldBeCalled();
        $this->action->setDownload($file);
    }

    public function testOverrideDownloadFile()
    {
        $this->service->hasFileServer()->willReturn(true);

        /** @var File $file1 */
        $file1 = $this->prophesize(File::class)->reveal();
        /** @var File $file2 */
        $file2 = $this->prophesize(File::class)->reveal();

        $this->transport->setBody($file1)->shouldBeCalled();
        $this->action->setDownload($file1);
        $this->transport->setBody($file2)->shouldBeCalled();
        $this->action->setDownload($file2);
    }

    public function testCallWithLocalFilesWithoutServer()
    {
        $this->service->hasFileServer()->willReturn(false);

        $this->transport->addCall(Argument::type(DeferCall::class))->shouldBeCalled();

        /** @var File $file */
        $file = $this->prophesize(File::class);
        $file->isLocal()->willReturn(true);

        $this->expectException(InvalidValueException::class);
        $this->expectExceptionMessage('File server not configured: "test" (1.0)');
        $this->action->call('service', '1.0.0', 'action', [], [$file->reveal()]);
    }

    public function testCallWithLocalFilesWithServer()
    {
        $this->service->hasFileServer()->willReturn(true);

        /** @var File $file */
        $file = $this->prophesize(File::class);
        $file->isLocal()->willReturn(true);
        $file = $file->reveal();

        $this->transport->addCall(Argument::any())->willReturn(true);
        $this->transport->addFile(
            Argument::any(),
            Argument::any(),
            Argument::any(),
            $file
        )->shouldBeCalled();

        $this->action->call('service', '1.0.0', 'action', [], [$file]);
    }

    public function testCallWithRemoteFilesWithoutServer()
    {
        $this->service->hasFileServer()->willReturn(false);

        /** @var File $file */
        $file = $this->prophesize(File::class);
        $file->isLocal()->willReturn(false);
        $file = $file->reveal();

        $this->transport->addCall(Argument::any())->willReturn(true);
        $this->transport->addFile(
            Argument::any(),
            Argument::any(),
            Argument::any(),
            $file
        )->shouldBeCalled();

        $this->action->call('service', '1.0.0', 'action', [], [$file]);
    }
}
