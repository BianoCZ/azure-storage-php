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
 * @copyright 2017 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */

namespace MicrosoftAzure\Storage\Blob\Models;

use DateTime;
use MicrosoftAzure\Storage\Blob\Internal\BlobResources as Resources;
use MicrosoftAzure\Storage\Common\Internal\Utilities;
use function intval;

/**
 * The result of calling appendBlock API.
 *
 * @category  Microsoft
 * @package   MicrosoftAzure\Storage\Blob\Models
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */
class AppendBlockResult
{

    private $appendOffset;

    private $committedBlockCount;

    private $contentMD5;

    private $etag;

    private $lastModified;

    private $requestServerEncrypted;

    /**
     * Creates AppendBlockResult object from the response of the put block request.
     *
     * @param array $headers The HTTP response headers in array representation.
     *
     * @internal
     *
     */
    public static function create(array $headers): AppendBlockResult
    {
        $result = new AppendBlockResult();

        $result->setAppendOffset(
            intval(
                Utilities::tryGetValueInsensitive(
                    Resources::X_MS_BLOB_APPEND_OFFSET,
                    $headers
                )
            )
        );

        $result->setCommittedBlockCount(
            intval(
                Utilities::tryGetValueInsensitive(
                    Resources::X_MS_BLOB_COMMITTED_BLOCK_COUNT,
                    $headers
                )
            )
        );

        $result->setContentMD5(
            Utilities::tryGetValueInsensitive(Resources::CONTENT_MD5, $headers)
        );

        $result->setEtag(
            Utilities::tryGetValueInsensitive(Resources::ETAG, $headers)
        );

        if (
            Utilities::arrayKeyExistsInsensitive(
                Resources::LAST_MODIFIED,
                $headers
            )
        ) {
            $lastModified = Utilities::tryGetValueInsensitive(
                Resources::LAST_MODIFIED,
                $headers
            );
            $lastModified = Utilities::rfc1123ToDateTime($lastModified);

            $result->setLastModified($lastModified);
        }

        $result->setRequestServerEncrypted(
            Utilities::toBoolean(
                Utilities::tryGetValueInsensitive(
                    Resources::X_MS_REQUEST_SERVER_ENCRYPTED,
                    $headers
                ),
                true
            )
        );

        return $result;
    }

    /**
     * Gets Etag of the blob that the client can use to perform conditional
     * PUT operations by using the If-Match request header.
     *
     */
    public function getEtag(): string
    {
        return $this->etag;
    }

    /**
     * Sets the etag value.
     *
     * @param string $etag etag as a string.
     *
     */
    protected function setEtag(string $etag): void
    {
        $this->etag = $etag;
    }

    /**
     * Gets $lastModified value.
     *
     */
    public function getLastModified(): DateTime
    {
        return $this->lastModified;
    }

    /**
     * Sets the $lastModified value.
     *
     * @param \DateTime $lastModified $lastModified value.
     *
     */
    protected function setLastModified(DateTime $lastModified): void
    {
        $this->lastModified = $lastModified;
    }

    /**
     * Gets block content MD5.
     *
     */
    public function getContentMD5(): string
    {
        return $this->contentMD5;
    }

    /**
     * Sets the content MD5 value.
     *
     * @param string $contentMD5 conent MD5 as a string.
     *
     */
    protected function setContentMD5(string $contentMD5): void
    {
        $this->contentMD5 = $contentMD5;
    }

    /**
     * Gets the offset at which the block was committed, in bytes.
     *
     */
    public function getAppendOffset(): int
    {
        return $this->appendOffset;
    }

    /**
     * Sets the offset at which the block was committed, in bytes.
     *
     * @param int $appendOffset append offset, in bytes.
     *
     */
    protected function setAppendOffset(int $appendOffset): void
    {
        $this->appendOffset = $appendOffset;
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
    protected function setCommittedBlockCount(int $committedBlockCount): void
    {
        $this->committedBlockCount = $committedBlockCount;
    }

    /**
     * Gets the whether the contents of the request are successfully encrypted.
     *
     */
    public function getRequestServerEncrypted(): bool
    {
        return $this->requestServerEncrypted;
    }

    /**
     * Sets the request server encryption value.
     *
     *
     */
    public function setRequestServerEncrypted(bool $requestServerEncrypted): void
    {
        $this->requestServerEncrypted = $requestServerEncrypted;
    }

}
