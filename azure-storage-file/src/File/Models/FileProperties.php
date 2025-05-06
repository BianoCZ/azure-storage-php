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
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */

namespace MicrosoftAzure\Storage\File\Models;

use DateTime;
use MicrosoftAzure\Storage\Common\Internal\Utilities;
use MicrosoftAzure\Storage\Common\Internal\Validate;
use MicrosoftAzure\Storage\File\Internal\FileResources as Resources;
use function array_change_key_case;

/**
 * Represents file properties
 *
 * @category  Microsoft
 * @package   MicrosoftAzure\Storage\File\Models
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */
class FileProperties
{

    private $lastModified;

    private $contentLength;

    private $contentType;

    private $etag;

    private $contentMD5;

    private $contentEncoding;

    private $contentLanguage;

    private $cacheControl;

    private $contentDisposition;

    private $contentRange;

    private $copyCompletionTime;

    private $copyStatusDescription;

    private $copyID;

    private $copyProgress;

    private $copySource;

    private $copyStatus;

    private $rangeContentMD5;

    /**
     * Creates FileProperties object from $parsed response in array
     * representation of http headers
     *
     * @param array $parsed parsed response in array format.
     *
     * @internal
     *
     */
    public static function createFromHttpHeaders(array $parsed): FileProperties
    {
        $result = new FileProperties();
        $clean  = array_change_key_case($parsed);

        $lastModified = Utilities::tryGetValue($parsed, Resources::LAST_MODIFIED);

        $result->setLastModified(
            Utilities::rfc1123ToDateTime($lastModified)
        );

        $result->setContentLength(
            Utilities::tryGetValue($parsed, Resources::CONTENT_LENGTH)
        );

        $result->setContentType(
            Utilities::tryGetValue($parsed, Resources::CONTENT_TYPE_LOWER_CASE)
        );

        $result->setETag(
            Utilities::tryGetValue($parsed, Resources::ETAG)
        );

        if (
            Utilities::tryGetValue($parsed, Resources::CONTENT_MD5) &&
            !Utilities::tryGetValue($parsed, Resources::CONTENT_RANGE)
        ) {
            $result->setContentMD5(
                Utilities::tryGetValue($parsed, Resources::CONTENT_MD5)
            );
        } else {
            $result->setContentMD5(
                Utilities::tryGetValue($parsed, Resources::FILE_CONTENT_MD5)
            );
            $result->setRangeContentMD5(
                Utilities::tryGetValue($parsed, Resources::CONTENT_MD5)
            );
        }

        $result->setContentEncoding(
            Utilities::tryGetValue($parsed, Resources::CONTENT_ENCODING)
        );

        $result->setContentLanguage(
            Utilities::tryGetValue($parsed, Resources::CONTENT_LANGUAGE)
        );

        $result->setCacheControl(
            Utilities::tryGetValue($parsed, Resources::CACHE_CONTROL)
        );

        $result->setContentDisposition(
            Utilities::tryGetValue($parsed, Resources::CONTENT_DISPOSITION)
        );

        $result->setContentRange(
            Utilities::tryGetValue($parsed, Resources::CONTENT_RANGE)
        );

        $result->setCopyCompletionTime(
            Utilities::tryGetValue($parsed, Resources::X_MS_COPY_COMPLETION_TIME)
        );

        $result->setCopyStatusDescription(
            Utilities::tryGetValue($parsed, Resources::X_MS_COPY_STATUS_DESCRIPTION)
        );

        $result->setCopyID(
            Utilities::tryGetValue($parsed, Resources::X_MS_COPY_ID)
        );

        $result->setCopyProgress(
            Utilities::tryGetValue($parsed, Resources::X_MS_COPY_PROGRESS)
        );

        $result->setCopySource(
            Utilities::tryGetValue($parsed, Resources::X_MS_COPY_SOURCE)
        );

        $result->setCopyStatus(
            Utilities::tryGetValue($parsed, Resources::X_MS_COPY_STATUS)
        );

        return $result;
    }

    /**
     * Gets file lastModified.
     *
     */
    public function getLastModified(): DateTime
    {
        return $this->lastModified;
    }

    /**
     * Sets file lastModified.
     *
     * @param \DateTime $lastModified value.
     *
     */
    protected function setLastModified(DateTime $lastModified): void
    {
        Validate::isDate($lastModified);
        $this->lastModified = $lastModified;
    }

    /**
     * Gets file etag.
     *
     */
    public function getETag(): string
    {
        return $this->etag;
    }

    /**
     * Sets file etag.
     *
     * @param string $etag value.
     *
     */
    protected function setETag(string $etag): void
    {
        $this->etag = $etag;
    }

    /**
     * Gets file contentType.
     *
     */
    public function getContentType(): string
    {
        return $this->contentType;
    }

    /**
     * Sets file contentType.
     *
     * @param string $contentType value.
     *
     */
    public function setContentType(string $contentType): void
    {
        $this->contentType = $contentType;
    }

    /**
     * Gets file contentRange.
     *
     */
    public function getContentRange(): string
    {
        return $this->contentRange;
    }

    /**
     * Sets file contentRange.
     *
     * @param string $contentRange value.
     *
     */
    protected function setContentRange(string $contentRange): void
    {
        $this->contentRange = $contentRange;
    }

    /**
     * Gets file contentLength.
     *
     */
    public function getContentLength(): int
    {
        return $this->contentLength;
    }

    /**
     * Sets file contentLength.
     *
     * @param int $contentLength value.
     *
     */
    public function setContentLength(int $contentLength): void
    {
        Validate::isInteger($contentLength, 'contentLength');
        $this->contentLength = (int) $contentLength;
    }

    /**
     * Gets file contentEncoding.
     *
     */
    public function getContentEncoding(): string
    {
        return $this->contentEncoding;
    }

    /**
     * Sets file contentEncoding.
     *
     * @param string $contentEncoding value.
     *
     */
    public function setContentEncoding(string $contentEncoding): void
    {
        $this->contentEncoding = $contentEncoding;
    }

    /**
     * Gets file contentLanguage.
     *
     */
    public function getContentLanguage(): string
    {
        return $this->contentLanguage;
    }

    /**
     * Sets file contentLanguage.
     *
     * @param string $contentLanguage value.
     *
     */
    public function setContentLanguage(string $contentLanguage): void
    {
        $this->contentLanguage = $contentLanguage;
    }

    /**
     * Gets file contentMD5.
     *
     */
    public function getContentMD5(): string
    {
        return $this->contentMD5;
    }

    /**
     * Sets file contentMD5.
     *
     * @param string $contentMD5 value.
     *
     */
    public function setContentMD5(string $contentMD5): void
    {
        $this->contentMD5 = $contentMD5;
    }

    /**
     * Gets file range contentMD5.
     *
     */
    public function getRangeContentMD5(): string
    {
        return $this->rangeContentMD5;
    }

    /**
     * Sets file range contentMD5.
     *
     * @param string rangeContentMD5 value.
     *
     */
    public function setRangeContentMD5(string $rangeContentMD5): void
    {
        $this->rangeContentMD5 = $rangeContentMD5;
    }

    /**
     * Gets file cacheControl.
     *
     */
    public function getCacheControl(): string
    {
        return $this->cacheControl;
    }

    /**
     * Sets file cacheControl.
     *
     * @param string $cacheControl value.
     *
     */
    public function setCacheControl(string $cacheControl): void
    {
        $this->cacheControl = $cacheControl;
    }

    /**
     * Gets file contentDisposition.
     *
     */
    public function getContentDisposition(): string
    {
        return $this->contentDisposition;
    }

    /**
     * Sets file contentDisposition.
     *
     * @param string $contentDisposition value.
     *
     */
    public function setContentDisposition(string $contentDisposition): void
    {
        $this->contentDisposition = $contentDisposition;
    }

    /**
     * Gets file copyCompletionTime.
     *
     */
    public function getCopyCompletionTime(): string
    {
        return $this->copyCompletionTime;
    }

    /**
     * Sets file copyCompletionTime.
     *
     * @param string $copyCompletionTime value.
     *
     */
    protected function setCopyCompletionTime(string $copyCompletionTime): void
    {
        $this->copyCompletionTime = $copyCompletionTime;
    }

    /**
     * Gets file copyStatusDescription.
     *
     */
    public function getCopyStatusDescription(): string
    {
        return $this->copyStatusDescription;
    }

    /**
     * Sets file copyStatusDescription.
     *
     * @param string $copyStatusDescription value.
     *
     */
    protected function setCopyStatusDescription(string $copyStatusDescription): void
    {
        $this->copyStatusDescription = $copyStatusDescription;
    }

    /**
     * Gets file lease state.
     *
     */
    public function getCopyID(): string
    {
        return $this->copyID;
    }

    /**
     * Sets file lease state.
     *
     * @param string $copyID value.
     *
     */
    protected function setCopyID(string $copyID): void
    {
        $this->copyID = $copyID;
    }

    /**
     * Gets file lease duration.
     *
     */
    public function getCopyProgress(): string
    {
        return $this->copyProgress;
    }

    /**
     * Sets file copyStatusDescription.
     *
     * @param string $copyProgress value.
     *
     */
    protected function setCopyProgress(string $copyProgress): void
    {
        $this->copyProgress = $copyProgress;
    }

    /**
     * Gets file copySource.
     *
     */
    public function getCopySource(): int
    {
        return $this->copySource;
    }

    /**
     * Sets file copySource.
     *
     * @param int $copySource value.
     *
     */
    protected function setCopySource(int $copySource): void
    {
        Validate::isInteger($copySource, 'copySource');
        $this->copySource = $copySource;
    }

    /**
     * Gets copy state of the file.
     *
     */
    public function getCopyStatus(): CopyStatus
    {
        return $this->copyStatus;
    }

    /**
     * Sets the copy state of the file.
     *
     * @param CopyStatus $copyStatus the copy state of the file.
     *
     */
    protected function setCopyStatus(CopyStatus $copyStatus): void
    {
        $this->copyStatus = $copyStatus;
    }

}
