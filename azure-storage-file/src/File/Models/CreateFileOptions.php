<?php

/**
 * LICENSE: The MIT License (the "License")
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * https://github.com/azure/azure-storage-php/LICENSE
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * PHP version 5
 *
 * @category  Microsoft
 * @package   MicrosoftAzure\Storage\File\Models
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2017 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */

namespace MicrosoftAzure\Storage\File\Models;

use MicrosoftAzure\Storage\Common\Internal\Validate;

/**
 * Optional parameters for createFile.
 *
 * @category  Microsoft
 * @package   MicrosoftAzure\Storage\File\Models
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2017 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */
class CreateFileOptions extends FileServiceOptions
{

    private $contentType;

    private $contentEncoding;

    private $contentLanguage;

    private $contentMD5;

    private $cacheControl;

    private $contentDisposition;

    private $metadata;

    private $contentLength;

    /**
     * Gets File contentType.
     *
     */
    public function getContentType(): string
    {
        return $this->contentType;
    }

    /**
     * Sets File contentType.
     *
     * @param string $contentType value.
     *
     */
    public function setContentType(string $contentType): void
    {
        $this->contentType = $contentType;
    }

    /**
     * Gets contentEncoding.
     *
     */
    public function getContentEncoding(): string
    {
        return $this->contentEncoding;
    }

    /**
     * Sets contentEncoding.
     *
     * @param string $contentEncoding value.
     *
     */
    public function setContentEncoding(string $contentEncoding): void
    {
        $this->contentEncoding = $contentEncoding;
    }

    /**
     * Gets contentLanguage.
     *
     */
    public function getContentLanguage(): string
    {
        return $this->contentLanguage;
    }

    /**
     * Sets contentLanguage.
     *
     * @param string $contentLanguage value.
     *
     */
    public function setContentLanguage(string $contentLanguage): void
    {
        $this->contentLanguage = $contentLanguage;
    }

    /**
     * Gets contentMD5.
     *
     */
    public function getContentMD5(): string
    {
        return $this->contentMD5;
    }

    /**
     * Sets contentMD5.
     *
     * @param string $contentMD5 value.
     *
     */
    public function setContentMD5(string $contentMD5): void
    {
        $this->contentMD5 = $contentMD5;
    }

    /**
     * Gets cacheControl.
     *
     */
    public function getCacheControl(): string
    {
        return $this->cacheControl;
    }

    /**
     * Sets cacheControl.
     *
     * @param string $cacheControl value to use.
     *
     */
    public function setCacheControl(string $cacheControl): void
    {
        $this->cacheControl = $cacheControl;
    }

    /**
     * Gets content disposition.
     *
     */
    public function getContentDisposition(): string
    {
        return $this->contentDisposition;
    }

    /**
     * Sets content disposition.
     *
     * @param string $contentDisposition value to use.
     *
     */
    public function setContentDisposition(string $contentDisposition): void
    {
        $this->contentDisposition = $contentDisposition;
    }

    /**
     * Gets File metadata.
     *
     */
    public function getMetadata(): array
    {
        return $this->metadata;
    }

    /**
     * Sets File metadata.
     *
     * @param array $metadata value.
     *
     */
    public function setMetadata(array $metadata): void
    {
        $this->metadata = $metadata;
    }

    /**
     * Gets File contentLength.
     *
     */
    public function getContentLength(): int
    {
        return $this->contentLength;
    }

    /**
     * Sets File contentLength.
     *
     * @param int $contentLength value.
     *
     */
    public function setContentLength(int $contentLength): void
    {
        Validate::isInteger($contentLength, 'contentLength');
        $this->contentLength = $contentLength;
    }

}
