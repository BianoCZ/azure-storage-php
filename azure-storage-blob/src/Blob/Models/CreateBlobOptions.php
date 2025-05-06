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

use MicrosoftAzure\Storage\Common\Internal\Validate;

/**
 * optional parameters for createXXXBlob wrapper
 *
 * @category  Microsoft
 * @package   MicrosoftAzure\Storage\Blob\Models
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */
class CreateBlobOptions extends BlobServiceOptions
{

    private $_contentType;

    private $_contentEncoding;

    private $_contentLanguage;

    private $_contentMD5;

    private $_cacheControl;

    private $_contentDisposition;

    private $_metadata;

    private $_sequenceNumber;

    private $_numberOfConcurrency;

    /**
     * Gets blob contentType.
     *
     */
    public function getContentType(): string
    {
        return $this->_contentType;
    }

    /**
     * Sets blob contentType.
     *
     * @param string $contentType value.
     *
     */
    public function setContentType(string $contentType): void
    {
        $this->_contentType = $contentType;
    }

    /**
     * Gets contentEncoding.
     *
     */
    public function getContentEncoding(): string
    {
        return $this->_contentEncoding;
    }

    /**
     * Sets contentEncoding.
     *
     * @param string $contentEncoding value.
     *
     */
    public function setContentEncoding(string $contentEncoding): void
    {
        $this->_contentEncoding = $contentEncoding;
    }

    /**
     * Gets contentLanguage.
     *
     */
    public function getContentLanguage(): string
    {
        return $this->_contentLanguage;
    }

    /**
     * Sets contentLanguage.
     *
     * @param string $contentLanguage value.
     *
     */
    public function setContentLanguage(string $contentLanguage): void
    {
        $this->_contentLanguage = $contentLanguage;
    }

    /**
     * Gets contentMD5.
     *
     */
    public function getContentMD5(): string
    {
        return $this->_contentMD5;
    }

    /**
     * Sets contentMD5.
     *
     * @param string $contentMD5 value.
     *
     */
    public function setContentMD5(string $contentMD5): void
    {
        $this->_contentMD5 = $contentMD5;
    }

    /**
     * Gets cacheControl.
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
     * Sets content disposition.
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
    public function setMetadata(array $metadata): void
    {
        $this->_metadata = $metadata;
    }

    /**
     * Gets blob sequenceNumber.
     *
     */
    public function getSequenceNumber(): int
    {
        return $this->_sequenceNumber;
    }

    /**
     * Sets blob sequenceNumber.
     *
     * @param int $sequenceNumber value.
     *
     */
    public function setSequenceNumber(int $sequenceNumber): void
    {
        Validate::isInteger($sequenceNumber, 'sequenceNumber');
        $this->_sequenceNumber = $sequenceNumber;
    }

    /**
     * Gets number of concurrency for sending a blob.
     *
     */
    public function getNumberOfConcurrency(): int
    {
        return $this->_numberOfConcurrency;
    }

    /**
     * Sets number of concurrency for sending a blob.
     *
     * @param int $numberOfConcurrency the number of concurrent requests.
     */
    public function setNumberOfConcurrency(int $numberOfConcurrency): void
    {
        $this->_numberOfConcurrency = $numberOfConcurrency;
    }

}
