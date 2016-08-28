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
 * Api class that encapsulate a file
 * @package Katana\Sdk\Api
 */
class File
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $filename;

    /**
     * @var int
     */
    private $size;

    /**
     * @var string
     */
    private $mime;

    /**
     * @var string
     */
    private $token;

    /**
     * File constructor.
     * @param string $name
     * @param string $path
     * @param string $mime
     * @param string $filename
     * @param string $size
     * @param string $token
     */
    public function __construct(
        $name,
        $path,
        $mime = null,
        $filename = null,
        $size = null,
        $token = null
    ) {
        $this->name = $name;
        $this->path = $path;

        if (!$mime) {
            $mime = $mime ?: mime_content_type($path);
        }

        if (!$filename) {
            $filename = basename($path);
        }

        if (!$size) {
            $file = new \SplFileInfo($path);
            $size = $file->getSize();
        }

        $this->filename = $filename;
        $this->size = $size;
        $this->mime = $mime;
        $this->token = $token;
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
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @return string
     */
    public function getMime()
    {
        return $this->mime;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Returns true if the file exists.
     *
     * File is considered to exist if the path begins with "file://" and
     * a file is found in that path in the filesystem or if path does not
     * begin with "file://".
     *
     * @return boolean
     */
    public function exists()
    {
        if (!$this->path) {
            return false;
        }

        if (strpos($this->path, 'file://') === 0) {
            return file_exists($this->path);
        }

        return true;
    }

    /**
     * @return string
     */
    public function read()
    {
        return file_get_contents($this->path);
    }

    /**
     * @param string $name
     * @return File
     */
    public function copyWithName($name)
    {
        return new static(
            $name,
            $this->path,
            $this->filename,
            $this->size,
            $this->mime,
            $this->token
        );
    }

    /**
     * @param string $mime
     * @return File
     */
    public function copyWithMime($mime)
    {
        return new static(
            $this->name,
            $this->path,
            $this->filename,
            $this->size,
            $mime,
            $this->token
        );
    }
}
