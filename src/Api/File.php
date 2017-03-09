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

use Katana\Sdk\Exception\InvalidValueException;

/**
 * Api class that encapsulate a file
 * @package Katana\Sdk\Api
 */
class File implements \Katana\Sdk\File
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
     * @param int $size
     * @param string $token
     * @throws InvalidValueException
     */
    public function __construct(
        $name,
        $path = '',
        $mime = '',
        $filename = '',
        $size = 0,
        $token = ''
    ) {
        $this->name = $name;
        if (!$path) {
            return;
        }

        $this->filename = $filename;
        $this->size = $size;
        $this->mime = $mime;
        $this->token = $token;

        if (strpos($path, 'http://') === 0) {
            if (!$mime) {
                throw new InvalidValueException("Missing mime for File: $name");
            }
            if (!$filename) {
                throw new InvalidValueException("Missing filename for File: $name");
            }
            if (!$size) {
                throw new InvalidValueException("Missing size for File: $name");
            }
            if (!$token) {
                throw new InvalidValueException("Missing token for File: $name");
            }
        } else {
            if (strpos($path, 'file://') === 0) {
                $filePath = substr($path, 7);
            } else {
                $filePath = $path;
            }
            $path = 'file://' . realpath($filePath);

            if (!file_exists($filePath)) {
                throw new InvalidValueException("File does not exist in path: $filePath");
            }
            if ($token) {
                throw new InvalidValueException("Unexpected token for File: $name");
            }

            if (!$mime) {
                $this->mime = mime_content_type($filePath);
            }

            if (!$filename) {
                $this->filename = basename($filePath);
            }

            if (!$size) {
                $file = new \SplFileInfo($filePath);
                $this->size = $file->getSize();
            }
        }

        $this->path = $path;
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
     * File is considered to exist if it has non empty path
     *
     * @return boolean
     */
    public function exists()
    {
        return (bool) $this->path;
    }

    /**
     * @return bool
     */
    public function isLocal()
    {
        return strpos($this->path, 'file://') === 0;
    }

    /**
     * @return string
     */
    public function read()
    {
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => "X-Token: $this->token"
            ]
        ]);

        return file_get_contents($this->path, null, $context);
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
