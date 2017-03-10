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

interface File
{
    /**
     * Return the name of the File.
     *
     * @return string
     */
    public function getName();

    /**
     * Return the full path for the File.
     *
     * @return string
     */
    public function getPath();

    /**
     * Return the MIME of the File.
     *
     * @return string
     */
    public function getMime();

    /**
     * Return the filename of the File without path.
     *
     * @return string
     */
    public function getFilename();

    /**
     * Return the size of the File in bytes.
     *
     * @return int
     */
    public function getSize();

    /**
     * Return the token for the file server where the File is hosted.
     *
     * @return string
     */
    public function getToken();

    /**
     * Determine if a path is defined for the File.
     *
     * @return bool
     */
    public function exists();

    /**
     * Determine if a File is local.
     *
     * @return bool
     */
    public function isLocal();

    /**
     * Return the contents of the file.
     *
     * @return string
     */
    public function read();

    /**
     * Return a copy of the File with the given name.
     *
     * @param string $name
     * @return self
     */
    public function copyWithName($name);

    /**
     * Return a copy of the File with the given MIME.
     *
     * @param string $mime
     * @return self
     */
    public function copyWithMime($mime);
}
