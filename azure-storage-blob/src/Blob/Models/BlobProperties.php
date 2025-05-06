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

use DateTime;
use MicrosoftAzure\Storage\Blob\Internal\BlobResources as Resources;
use MicrosoftAzure\Storage\Common\Internal\Utilities;
use MicrosoftAzure\Storage\Common\Internal\Validate;
use function array_change_key_case;
use function intval;
use function is_null;

/**
 * Represents blob properties
 *
 * @category  Microsoft
 * @package   MicrosoftAzure\Storage\Blob\Models
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */
class BlobProperties
{

    private $lastModified;

    private $creationTime;

    private $etag;

    private $contentType;

    private $contentLength;

    private $contentEncoding;

    private $contentLanguage;

    private $contentMD5;

    private $contentRange;

    private $cacheControl;

    private $contentDisposition;

    private $blobType;

    private $leaseStatus;

    private $leaseState;

    private $leaseDuration;

    private $sequenceNumber;

    private $serverEncrypted;

    private $committedBlockCount;

    private $copyState;

    private $copyDestinationSnapshot;

    private $incrementalCopy;

    private $rangeContentMD5;

    private $accessTier;

    private $accessTierInferred;

    private $accessTierChangeTime;

    private $archiveStatus;

    private $deletedTime;

    private $remainingRetentionDays;

    /**
     * Creates BlobProperties object from $parsed response in array representation of XML elements
     *
     * @param array $parsed parsed response in array format.
     *
     * @internal
     *
     */
    public static function createFromXml(array $parsed): BlobProperties
    {
        $result = new BlobProperties();
        $clean  = array_change_key_case($parsed);

        $result->setCommonBlobProperties($clean);
        $result->setLeaseStatus(Utilities::tryGetValue($clean, 'leasestatus'));
        $result->setLeaseState(Utilities::tryGetValue($clean, 'leasestate'));
        $result->setLeaseDuration(Utilities::tryGetValue($clean, 'leaseduration'));
        $result->setCopyState(CopyState::createFromXml($clean));

        $result->setIncrementalCopy(
            Utilities::toBoolean(
                Utilities::tryGetValue($clean, 'incrementalcopy'),
                true
            )
        );

        $result->setAccessTier(Utilities::tryGetValue($clean, 'accesstier'));

        $result->setAccessTierInferred(
            Utilities::toBoolean(
                Utilities::tryGetValue($clean, 'accesstierinferred'),
                true
            )
        );

        $accesstierchangetime = Utilities::tryGetValue($clean, 'accesstierchangetime');
        if (!is_null($accesstierchangetime)) {
            $accesstierchangetime = Utilities::rfc1123ToDateTime($accesstierchangetime);
            $result->setAccessTierChangeTime($accesstierchangetime);
        }

        $result->setArchiveStatus(
            Utilities::tryGetValue($clean, 'archivestatus')
        );

        $deletedtime = Utilities::tryGetValue($clean, 'deletedtime');
        if (!is_null($deletedtime)) {
            $deletedtime = Utilities::rfc1123ToDateTime($deletedtime);
            $result->setDeletedTime($deletedtime);
        }

        $remainingretentiondays = Utilities::tryGetValue($clean, 'remainingretentiondays');
        if (!is_null($remainingretentiondays)) {
            $result->setRemainingRetentionDays((int) $remainingretentiondays);
        }

        $creationtime = Utilities::tryGetValue($clean, 'creation-time');
        if (!is_null($creationtime)) {
            $creationtime = Utilities::rfc1123ToDateTime($creationtime);
            $result->setCreationTime($creationtime);
        }

        return $result;
    }

    /**
     * Creates BlobProperties object from $parsed response in array representation of http headers
     *
     * @param array $parsed parsed response in array format.
     *
     * @internal
     *
     */
    public static function createFromHttpHeaders(array $parsed): BlobProperties
    {
        $result = new BlobProperties();
        $clean  = array_change_key_case($parsed);

        $result->setCommonBlobProperties($clean);

        $result->setBlobType(Utilities::tryGetValue($clean, Resources::X_MS_BLOB_TYPE));
        $result->setLeaseStatus(Utilities::tryGetValue($clean, Resources::X_MS_LEASE_STATUS));
        $result->setLeaseState(Utilities::tryGetValue($clean, Resources::X_MS_LEASE_STATE));
        $result->setLeaseDuration(Utilities::tryGetValue($clean, Resources::X_MS_LEASE_DURATION));
        $result->setCopyState(CopyState::createFromHttpHeaders($clean));

        $result->setServerEncrypted(
            Utilities::toBoolean(
                Utilities::tryGetValue(
                    $clean,
                    Resources::X_MS_SERVER_ENCRYPTED
                ),
                true
            )
        );
        $result->setIncrementalCopy(
            Utilities::toBoolean(
                Utilities::tryGetValue(
                    $clean,
                    Resources::X_MS_INCREMENTAL_COPY
                ),
                true
            )
        );
        $result->setCommittedBlockCount(
            intval(Utilities::tryGetValue(
                $clean,
                Resources::X_MS_BLOB_COMMITTED_BLOCK_COUNT
            ))
        );
        $result->setCopyDestinationSnapshot(
            Utilities::tryGetValue(
                $clean,
                Resources::X_MS_COPY_DESTINATION_SNAPSHOT
            )
        );

        $result->setAccessTier(Utilities::tryGetValue($clean, Resources::X_MS_ACCESS_TIER));

        $result->setAccessTierInferred(
            Utilities::toBoolean(
                Utilities::tryGetValue($clean, Resources::X_MS_ACCESS_TIER_INFERRED),
                true
            )
        );

        $date = Utilities::tryGetValue($clean, Resources::X_MS_ACCESS_TIER_CHANGE_TIME);
        if (!is_null($date)) {
            $date = Utilities::rfc1123ToDateTime($date);
            $result->setAccessTierChangeTime($date);
        }

        $result->setArchiveStatus(
            Utilities::tryGetValue($clean, Resources::X_MS_ARCHIVE_STATUS)
        );

        return $result;
    }

    /**
     * Gets blob lastModified.
     *
     */
    public function getLastModified(): DateTime
    {
        return $this->lastModified;
    }

    /**
     * Sets blob lastModified.
     *
     * @param \DateTime $lastModified value.
     *
     */
    public function setLastModified(DateTime $lastModified): void
    {
        Validate::isDate($lastModified);
        $this->lastModified = $lastModified;
    }

    /**
     * Gets blob creationTime.
     *
     */
    public function getCreationTime(): DateTime
    {
        return $this->creationTime;
    }

    /**
     * Sets blob creationTime.
     *
     * @param \DateTime $creationTime value.
     *
     */
    public function setCreationTime(DateTime $creationTime): void
    {
        Validate::isDate($creationTime);
        $this->creationTime = $creationTime;
    }

    /**
     * Gets blob etag.
     *
     */
    public function getETag(): string
    {
        return $this->etag;
    }

    /**
     * Sets blob etag.
     *
     * @param string $etag value.
     *
     */
    public function setETag(string $etag): void
    {
        $this->etag = $etag;
    }

    /**
     * Gets blob contentType.
     *
     */
    public function getContentType(): string
    {
        return $this->contentType;
    }

    /**
     * Sets blob contentType.
     *
     * @param string $contentType value.
     *
     */
    public function setContentType(string $contentType): void
    {
        $this->contentType = $contentType;
    }

    /**
     * Gets blob contentRange.
     *
     */
    public function getContentRange(): string
    {
        return $this->contentRange;
    }

    /**
     * Sets blob contentRange.
     *
     * @param string $contentRange value.
     *
     */
    public function setContentRange(string $contentRange): void
    {
        $this->contentRange = $contentRange;
    }

    /**
     * Gets blob contentLength.
     *
     */
    public function getContentLength(): int
    {
        return $this->contentLength;
    }

    /**
     * Sets blob contentLength.
     *
     * @param int $contentLength value.
     *
     */
    public function setContentLength(int $contentLength): void
    {
        Validate::isInteger($contentLength, 'contentLength');
        $this->contentLength = $contentLength;
    }

    /**
     * Gets blob contentEncoding.
     *
     */
    public function getContentEncoding(): string
    {
        return $this->contentEncoding;
    }

    /**
     * Sets blob contentEncoding.
     *
     * @param string $contentEncoding value.
     *
     */
    public function setContentEncoding(string $contentEncoding): void
    {
        $this->contentEncoding = $contentEncoding;
    }

    /**
     * Gets blob access tier.
     *
     */
    public function getAccessTier(): string
    {
        return $this->accessTier;
    }

    /**
     * Sets blob access tier.
     *
     * @param string $accessTier value.
     *
     */
    public function setAccessTier(string $accessTier): void
    {
        $this->accessTier = $accessTier;
    }

    /**
     * Gets blob archive status.
     *
     */
    public function getArchiveStatus(): string
    {
        return $this->archiveStatus;
    }

    /**
     * Sets blob archive status.
     *
     * @param string $archiveStatus value.
     *
     */
    public function setArchiveStatus(string $archiveStatus): void
    {
        $this->archiveStatus = $archiveStatus;
    }

    /**
     * Gets blob deleted time.
     *
     */
    public function getDeletedTime(): string
    {
        return $this->deletedTime;
    }

    /**
     * Sets blob deleted time.
     *
     * @param \DateTime $deletedTime value.
     *
     */
    public function setDeletedTime(DateTime $deletedTime): void
    {
        $this->deletedTime = $deletedTime;
    }

    /**
     * Gets blob remaining retention days.
     *
     */
    public function getRemainingRetentionDays(): int
    {
        return $this->remainingRetentionDays;
    }

    /**
     * Sets blob remaining retention days.
     *
     * @param int $remainingRetentionDays value.
     *
     */
    public function setRemainingRetentionDays(int $remainingRetentionDays): void
    {
        $this->remainingRetentionDays = $remainingRetentionDays;
    }

    /**
     * Gets blob access inferred.
     *
     */
    public function getAccessTierInferred(): bool
    {
        return $this->accessTierInferred;
    }

    /**
     * Sets blob access tier inferred.
     *
     * @param bool $accessTierInferred value.
     *
     */
    public function setAccessTierInferred(bool $accessTierInferred): void
    {
        Validate::isBoolean($accessTierInferred);
        $this->accessTierInferred = $accessTierInferred;
    }

    /**
     * Gets blob access tier change time.
     *
     */
    public function getAccessTierChangeTime(): DateTime
    {
        return $this->accessTierChangeTime;
    }

    /**
     * Sets blob access tier change time.
     *
     * @param \DateTime $accessTierChangeTime value.
     *
     */
    public function setAccessTierChangeTime(DateTime $accessTierChangeTime): void
    {
        Validate::isDate($accessTierChangeTime);
        $this->accessTierChangeTime = $accessTierChangeTime;
    }

    /**
     * Gets blob contentLanguage.
     *
     */
    public function getContentLanguage(): string
    {
        return $this->contentLanguage;
    }

    /**
     * Sets blob contentLanguage.
     *
     * @param string $contentLanguage value.
     *
     */
    public function setContentLanguage(string $contentLanguage): void
    {
        $this->contentLanguage = $contentLanguage;
    }

    /**
     * Gets blob contentMD5.
     *
     */
    public function getContentMD5(): string
    {
        return $this->contentMD5;
    }

    /**
     * Sets blob contentMD5.
     *
     * @param string $contentMD5 value.
     *
     */
    public function setContentMD5(string $contentMD5): void
    {
        $this->contentMD5 = $contentMD5;
    }

    /**
     * Gets blob range contentMD5.
     *
     */
    public function getRangeContentMD5(): string
    {
        return $this->rangeContentMD5;
    }

    /**
     * Sets blob range contentMD5.
     *
     * @param string rangeContentMD5 value.
     *
     */
    public function setRangeContentMD5($rangeContentMD5): void
    {
        $this->rangeContentMD5 = $rangeContentMD5;
    }

    /**
     * Gets blob cacheControl.
     *
     */
    public function getCacheControl(): string
    {
        return $this->cacheControl;
    }

    /**
     * Sets blob cacheControl.
     *
     * @param string $cacheControl value.
     *
     */
    public function setCacheControl(string $cacheControl): void
    {
        $this->cacheControl = $cacheControl;
    }

    /**
     * Gets blob contentDisposition.
     *
     */
    public function getContentDisposition(): string
    {
        return $this->contentDisposition;
    }

    /**
     * Sets blob contentDisposition.
     *
     * @param string $contentDisposition value.
     *
     */
    public function setContentDisposition(string $contentDisposition): void
    {
        $this->contentDisposition = $contentDisposition;
    }

    /**
     * Gets blob blobType.
     *
     */
    public function getBlobType(): string
    {
        return $this->blobType;
    }

    /**
     * Sets blob blobType.
     *
     * @param string $blobType value.
     *
     */
    public function setBlobType(string $blobType): void
    {
        $this->blobType = $blobType;
    }

    /**
     * Gets blob leaseStatus.
     *
     */
    public function getLeaseStatus(): string
    {
        return $this->leaseStatus;
    }

    /**
     * Sets blob leaseStatus.
     *
     * @param string $leaseStatus value.
     *
     */
    public function setLeaseStatus(string $leaseStatus): void
    {
        $this->leaseStatus = $leaseStatus;
    }

    /**
     * Gets blob lease state.
     *
     */
    public function getLeaseState(): string
    {
        return $this->leaseState;
    }

    /**
     * Sets blob lease state.
     *
     * @param string $leaseState value.
     *
     */
    public function setLeaseState(string $leaseState): void
    {
        $this->leaseState = $leaseState;
    }

    /**
     * Gets blob lease duration.
     *
     */
    public function getLeaseDuration(): string
    {
        return $this->leaseDuration;
    }

    /**
     * Sets blob leaseStatus.
     *
     * @param string $leaseDuration value.
     *
     */
    public function setLeaseDuration(string $leaseDuration): void
    {
        $this->leaseDuration = $leaseDuration;
    }

    /**
     * Gets blob sequenceNumber.
     *
     */
    public function getSequenceNumber(): int
    {
        return $this->sequenceNumber;
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
        $this->sequenceNumber = $sequenceNumber;
    }

    /**
     * Gets the server encryption status of the blob.
     *
     */
    public function getServerEncrypted(): bool
    {
        return $this->serverEncrypted;
    }

    /**
     * Sets the server encryption status of the blob.
     *
     *
     */
    public function setServerEncrypted(bool $serverEncrypted): void
    {
        $this->serverEncrypted = $serverEncrypted;
    }

    /**
     * Gets the number of committed blocks present in the blob.
     *
     */
    public function getCommittedBlockCount(): int
    {
        return $this->committedBlockCount;
    }

    /**
     * Sets the number of committed blocks present in the blob.
     *
     * @param int $committedBlockCount the number of committed blocks present in the blob.
     *
     */
    public function setCommittedBlockCount(int $committedBlockCount): void
    {
        $this->committedBlockCount = $committedBlockCount;
    }

    /**
     * Gets copy state of the blob.
     *
     */
    public function getCopyState(): CopyState
    {
        return $this->copyState;
    }

    /**
     * Sets the copy state of the blob.
     *
     * @param CopyState $copyState the copy state of the blob.
     *
     */
    public function setCopyState(CopyState $copyState): void
    {
        $this->copyState = $copyState;
    }

    /**
     * Gets snapshot time of the last successful incremental copy snapshot for this blob.
     *
     */
    public function getCopyDestinationSnapshot(): string
    {
        return $this->copyDestinationSnapshot;
    }

    /**
     * Sets snapshot time of the last successful incremental copy snapshot for this blob.
     *
     * @param string $copyDestinationSnapshot last successful incremental copy snapshot.
     */
    public function setCopyDestinationSnapshot(string $copyDestinationSnapshot): void
    {
        $this->copyDestinationSnapshot = $copyDestinationSnapshot;
    }

    /**
     * Gets whether the blob is an incremental copy blob.
     *
     */
    public function getIncrementalCopy(): bool
    {
        return $this->incrementalCopy;
    }

    /**
     * Sets whether the blob is an incremental copy blob.
     *
     * @param bool $incrementalCopy whether blob is an incremental copy blob.
     */
    public function setIncrementalCopy(bool $incrementalCopy): void
    {
        $this->incrementalCopy = $incrementalCopy;
    }

    private function setCommonBlobProperties(array $clean): void
    {
        $date = Utilities::tryGetValue($clean, Resources::LAST_MODIFIED);
        if (!is_null($date)) {
            $date = Utilities::rfc1123ToDateTime($date);
            $this->setLastModified($date);
        }

        $this->setBlobType(Utilities::tryGetValue($clean, 'blobtype'));

        $this->setContentLength(intval($clean[Resources::CONTENT_LENGTH]));
        $this->setETag(Utilities::tryGetValue($clean, Resources::ETAG));
        $this->setSequenceNumber(
            intval(
                Utilities::tryGetValue($clean, Resources::X_MS_BLOB_SEQUENCE_NUMBER)
            )
        );
        $this->setContentRange(
            Utilities::tryGetValue($clean, Resources::CONTENT_RANGE)
        );
        $this->setCacheControl(
            Utilities::tryGetValue($clean, Resources::CACHE_CONTROL)
        );
        $this->setContentDisposition(
            Utilities::tryGetValue($clean, Resources::CONTENT_DISPOSITION)
        );
        $this->setContentEncoding(
            Utilities::tryGetValue($clean, Resources::CONTENT_ENCODING)
        );
        $this->setContentLanguage(
            Utilities::tryGetValue($clean, Resources::CONTENT_LANGUAGE)
        );
        $this->setContentType(
            Utilities::tryGetValue($clean, Resources::CONTENT_TYPE_LOWER_CASE)
        );

        if (
            Utilities::tryGetValue($clean, Resources::CONTENT_MD5) &&
            !Utilities::tryGetValue($clean, Resources::CONTENT_RANGE)
        ) {
            $this->setContentMD5(
                Utilities::tryGetValue($clean, Resources::CONTENT_MD5)
            );
        } else {
            $this->setContentMD5(
                Utilities::tryGetValue($clean, Resources::BLOB_CONTENT_MD5)
            );
            $this->setRangeContentMD5(
                Utilities::tryGetValue($clean, Resources::CONTENT_MD5)
            );
        }
    }

}
