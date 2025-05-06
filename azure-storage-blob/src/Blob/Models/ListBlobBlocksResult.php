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
use function array_key_exists;
use function intval;
use function is_array;
use function is_null;

/**
 * Holds result of listBlobBlocks
 *
 * @category  Microsoft
 * @package   MicrosoftAzure\Storage\Blob\Models
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */
class ListBlobBlocksResult
{

    private $lastModified;

    private $etag;

    private $contentType;

    private $contentLength;

    private $committedBlocks;

    private $uncommittedBlocks;

    /**
     * Gets block entries from parsed response
     *
     * @param array  $parsed HTTP response
     * @param string $type   Block type
     *
     */
    private static function getEntries(array $parsed, string $type): array
    {
        $entries = [];

        if (is_array($parsed)) {
            $rawEntries = [];

            if (
                array_key_exists($type, $parsed)
                && is_array($parsed[$type])
                && !empty($parsed[$type])
            ) {
                $rawEntries = Utilities::getArray($parsed[$type]['Block']);
            }

            foreach ($rawEntries as $value) {
                $entries[$value['Name']] = $value['Size'];
            }
        }

        return $entries;
    }

    /**
     * Creates ListBlobBlocksResult from given response headers and parsed body
     *
     * @param array $headers HTTP response headers
     * @param array $parsed  HTTP response body in array representation
     *
     * @internal
     *
     */
    public static function create(array $headers, array $parsed): ListBlobBlocksResult
    {
        $result = new ListBlobBlocksResult();
        $clean  = array_change_key_case($headers);

        $result->setETag(Utilities::tryGetValue($clean, Resources::ETAG));
        $date = Utilities::tryGetValue($clean, Resources::LAST_MODIFIED);
        if (!is_null($date)) {
            $date = Utilities::rfc1123ToDateTime($date);
            $result->setLastModified($date);
        }
        $result->setContentLength(
            intval(
                Utilities::tryGetValue($clean, Resources::X_MS_BLOB_CONTENT_LENGTH)
            )
        );
        $result->setContentType(
            Utilities::tryGetValue($clean, Resources::CONTENT_TYPE_LOWER_CASE)
        );

        $result->uncommittedBlocks = self::getEntries(
            $parsed,
            'UncommittedBlocks'
        );
        $result->committedBlocks   = self::getEntries($parsed, 'CommittedBlocks');

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
    protected function setLastModified(DateTime $lastModified): void
    {
        Validate::isDate($lastModified);
        $this->lastModified = $lastModified;
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
    protected function setETag(string $etag): void
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
    protected function setContentType(string $contentType): void
    {
        $this->contentType = $contentType;
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
    protected function setContentLength(int $contentLength): void
    {
        Validate::isInteger($contentLength, 'contentLength');
        $this->contentLength = $contentLength;
    }

    /**
     * Gets uncommitted blocks
     *
     */
    public function getUncommittedBlocks(): array
    {
        return $this->uncommittedBlocks;
    }

    /**
     * Sets uncommitted blocks
     *
     * @param array $uncommittedBlocks The uncommitted blocks entries
     *
     */
    protected function setUncommittedBlocks(array $uncommittedBlocks): void
    {
        $this->uncommittedBlocks = $uncommittedBlocks;
    }

    /**
     * Gets committed blocks
     *
     */
    public function getCommittedBlocks(): array
    {
        return $this->committedBlocks;
    }

    /**
     * Sets committed blocks
     *
     * @param array $committedBlocks The committed blocks entries
     *
     */
    protected function setCommittedBlocks(array $committedBlocks): void
    {
        $this->committedBlocks = $committedBlocks;
    }

}
