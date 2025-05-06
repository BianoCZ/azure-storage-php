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
 * @package   MicrosoftAzure\Storage\Blob\Models
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */

namespace MicrosoftAzure\Storage\Blob\Models;

/**
 * Optional parameters for commitBlobBlocks
 *
 * @category  Microsoft
 * @package   MicrosoftAzure\Storage\Blob\Models
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */
class CommitBlobBlocksOptions extends BlobServiceOptions
{

    private $_contentType;

    private $_contentEncoding;

    private $_contentLanguage;

    private $_contentMD5;

    private $_cacheControl;

    private $_contentDisposition;

    private $_metadata;

    /**
     * Gets ContentType.
     *
     */
    public function getContentType(): string
    {
        return $this->_contentType;
    }

    /**
     * Sets ContentType.
     *
     * @param string $contentType value.
     *
     */
    public function setContentType(string $contentType): void
    {
        $this->_contentType = $contentType;
    }

    /**
     * Gets ContentEncoding.
     *
     */
    public function getContentEncoding(): string
    {
        return $this->_contentEncoding;
    }

    /**
     * Sets ContentEncoding.
     *
     * @param string $contentEncoding value.
     *
     */
    public function setContentEncoding(string $contentEncoding): void
    {
        $this->_contentEncoding = $contentEncoding;
    }

    /**
     * Gets ContentLanguage.
     *
     */
    public function getContentLanguage(): string
    {
        return $this->_contentLanguage;
    }

    /**
     * Sets ContentLanguage.
     *
     * @param string $contentLanguage value.
     *
     */
    public function setContentLanguage(string $contentLanguage): void
    {
        $this->_contentLanguage = $contentLanguage;
    }

    /**
     * Gets ContentMD5.
     *
     */
    public function getContentMD5(): string
    {
        return $this->_contentMD5;
    }

    /**
     * Sets ContentMD5.
     *
     * @param string $contentMD5 value.
     *
     */
    public function setContentMD5(string $contentMD5): void
    {
        $this->_contentMD5 = $contentMD5;
    }

    /**
     * Gets cache control.
     *
     */
    public function getCacheControl(): string
    {
        return $this->_cacheControl;
    }

    /**
     * Sets cacheControl.
     *
     * @param string $cacheControl value to use.
     *
     */
    public function setCacheControl(string $cacheControl): void
    {
        $this->_cacheControl = $cacheControl;
    }

    /**
     * Gets content disposition.
     *
     */
    public function getContentDisposition(): string
    {
        return $this->_contentDisposition;
    }

    /**
     * Sets contentDisposition.
     *
     * @param string $contentDisposition value to use.
     *
     */
    public function setContentDisposition(string $contentDisposition): void
    {
        $this->_contentDisposition = $contentDisposition;
    }

    /**
     * Gets blob metadata.
     *
     */
    public function getMetadata(): array
    {
        return $this->_metadata;
    }

    /**
     * Sets blob metadata.
     *
     * @param array $metadata value.
     *
     */
    public function setMetadata(?array $metadata = null): void
    {
        $this->_metadata = $metadata;
    }

    /**
     * Create a instance using the given options
     *
     * @param  mixed $options Input options
     *
     * @internal
     *
     */
    public static function create(mixed $options): self
    {
        $result = new CommitBlobBlocksOptions();
        $result->setContentType($options->getContentType());
        $result->setContentEncoding($options->getContentEncoding());
        $result->setContentLanguage($options->getContentLanguage());
        $result->setContentMD5($options->getContentMD5());
        $result->setCacheControl($options->getCacheControl());
        $result->setContentDisposition($options->getContentDisposition());
        $result->setMetadata($options->getMetadata());
        $result->setLeaseId($options->getLeaseId());
        $result->setAccessConditions($options->getAccessConditions());

        return $result;
    }

}
