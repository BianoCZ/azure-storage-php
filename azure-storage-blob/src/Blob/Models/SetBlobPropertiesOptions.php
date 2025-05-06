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

use function is_null;

/**
 * Optional parameters for setBlobProperties wrapper
 *
 * @category  Microsoft
 * @package   MicrosoftAzure\Storage\Blob\Models
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */
class SetBlobPropertiesOptions extends BlobServiceOptions
{

    private $_blobProperties;

    private $_sequenceNumberAction;

    /**
     * Creates a new SetBlobPropertiesOptions with a specified BlobProperties
     * instance.
     *
     * @param BlobProperties $blobProperties The blob properties instance.
     */
    public function __construct(?BlobProperties $blobProperties = null)
    {
        parent::__construct();

        $this->_blobProperties = is_null($blobProperties)
                                 ? new BlobProperties() : clone $blobProperties;
    }

    /**
     * Gets blob sequenceNumber.
     *
     */
    public function getSequenceNumber(): int
    {
        return $this->_blobProperties->getSequenceNumber();
    }

    /**
     * Sets blob sequenceNumber.
     *
     * @param int $sequenceNumber value.
     *
     */
    public function setSequenceNumber(int $sequenceNumber): void
    {
        $this->_blobProperties->setSequenceNumber($sequenceNumber);
    }

    /**
     * Gets lease Id for the blob
     *
     */
    public function getSequenceNumberAction(): string
    {
        return $this->_sequenceNumberAction;
    }

    /**
     * Sets lease Id for the blob
     *
     * @param string $sequenceNumberAction action.
     *
     */
    public function setSequenceNumberAction(string $sequenceNumberAction): void
    {
        $this->_sequenceNumberAction = $sequenceNumberAction;
    }

    /**
     * Gets blob contentLength.
     *
     */
    public function getContentLength(): int
    {
        return $this->_blobProperties->getContentLength();
    }

    /**
     * Sets blob contentLength.
     *
     * @param int $contentLength value.
     *
     */
    public function setContentLength(int $contentLength): void
    {
        $this->_blobProperties->setContentLength($contentLength);
    }

    /**
     * Gets ContentType.
     *
     */
    public function getContentType(): string
    {
        return $this->_blobProperties->getContentType();
    }

    /**
     * Sets ContentType.
     *
     * @param string $contentType value.
     *
     */
    public function setContentType(string $contentType): void
    {
        $this->_blobProperties->setContentType($contentType);
    }

    /**
     * Gets ContentEncoding.
     *
     */
    public function getContentEncoding(): string
    {
        return $this->_blobProperties->getContentEncoding();
    }

    /**
     * Sets ContentEncoding.
     *
     * @param string $contentEncoding value.
     *
     */
    public function setContentEncoding(string $contentEncoding): void
    {
        $this->_blobProperties->setContentEncoding($contentEncoding);
    }

    /**
     * Gets ContentLanguage.
     *
     */
    public function getContentLanguage(): string
    {
        return $this->_blobProperties->getContentLanguage();
    }

    /**
     * Sets ContentLanguage.
     *
     * @param string $contentLanguage value.
     *
     */
    public function setContentLanguage(string $contentLanguage): void
    {
        $this->_blobProperties->setContentLanguage($contentLanguage);
    }

    /**
     * Gets ContentMD5.
     *
     * @return void
     */
    public function getContentMD5()
    {
        return $this->_blobProperties->getContentMD5();
    }

    /**
     * Sets blob ContentMD5.
     *
     * @param string $contentMD5 value.
     *
     */
    public function setContentMD5(string $contentMD5): void
    {
        $this->_blobProperties->setContentMD5($contentMD5);
    }

    /**
     * Gets cache control.
     *
     */
    public function getCacheControl(): string
    {
        return $this->_blobProperties->getCacheControl();
    }

    /**
     * Sets cacheControl.
     *
     * @param string $cacheControl value to use.
     *
     */
    public function setCacheControl(string $cacheControl): void
    {
        $this->_blobProperties->setCacheControl($cacheControl);
    }

    /**
     * Gets content disposition.
     *
     */
    public function getContentDisposition(): string
    {
        return $this->_blobProperties->getContentDisposition();
    }

    /**
     * Sets contentDisposition.
     *
     * @param string $contentDisposition value to use.
     *
     */
    public function setContentDisposition(string $contentDisposition): void
    {
        $this->_blobProperties->setContentDisposition($contentDisposition);
    }

}
