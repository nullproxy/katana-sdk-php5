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

use Katana\Sdk\Logger\KatanaLogger;

class KatanaLoggerTest extends PHPUnit_Framework_TestCase
{
    public function testDebugLog()
    {
        $logger = new KatanaLogger();

        $this->expectOutputRegex('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}\.\d{1,5}Z \[DEBUG\] \[SDK\] Test log |123|$/');
        $logger->debug('Test log', '123');
    }
}
