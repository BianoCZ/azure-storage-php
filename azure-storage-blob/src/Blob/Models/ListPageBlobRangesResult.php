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
use MicrosoftAzure\Storage\Common\Models\Range;
use function array_change_key_case;
use function intval;
use function strtolower;

/**
 * Holds result of calling listPageBlobRanges wrapper
 *
 * @category  Microsoft
 * @package   MicrosoftAzure\Storage\Blob\Models
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */
class ListPageBlobRangesResult
{

    private $_lastModified;

    private $_etag;

    private $_contentLength;

    private $_pageRanges;

    /**
     * Creates BlobProperties object from $parsed response in array representation
     *
     * @param array $headers HTTP response headers
     * @param array $parsed  parsed response in array format.
     *
     * @internal
     *
     */
    public static function create(array $headers, ?array $parsed = null): ListPageBlobRangesResult
    {
        $result  = new ListPageBlobRangesResult();
        $headers = array_change_key_case($headers);

        $date          = $headers[Resources::LAST_MODIFIED];
        $date          = Utilities::rfc1123ToDateTime($date);
        $blobLength    = intval($headers[Resources::X_MS_BLOB_CONTENT_LENGTH]);
        $rawRanges = [];

        if (!empty($parsed[Resources::XTAG_PAGE_RANGE])) {
            $parsed        = array_change_key_case($parsed);
            $rawRanges = Utilities::getArray($parsed[strtolower(RESOURCES::XTAG_PAGE_RANGE)]);
        }

        $pageRanges = [];
        foreach ($rawRanges as $value) {
            $pageRanges[] = new Range(
                intval($value[Resources::XTAG_RANGE_START]),
                intval($value[Resources::XTAG_RANGE_END])
            );
        }
        $result->setRanges($pageRanges);
        $result->setContentLength($blobLength);
        $result->setETag($headers[Resources::ETAG]);
        $result->setLastModified($date);

        return $result;
    }

    /**
     * Gets blob lastModified.
     *
     */
    public function getLastModified(): DateTime
    {
        return $this->_lastModified;
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
        $this->_lastModified = $lastModified;
    }

    /**
     * Gets blob etag.
     *
     */
    public function getETag(): string
    {
        return $this->_etag;
    }

    /**
     * Sets blob etag.
     *
     * @param string $etag value.
     *
     */
    protected function setETag(string $etag): void
    {
        Validate::canCastAsString($etag, 'etag');
        $this->_etag = $etag;
    }

    /**
     * Gets blob contentLength.
     *
     */
    public function getContentLength(): int
    {
        return $this->_contentLength;
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
        $this->_contentLength = $contentLength;
    }

    /**
     * Gets page ranges
     *
     */
    public function getRanges(): array
    {
        return $this->_pageRanges;
    }

    /**
     * Sets page ranges
     *
     * @param array $pageRanges page ranges to set
     *
     */
    protected function setRanges(array $pageRanges): void
    {
        $this->_pageRanges = [];
        foreach ($pageRanges as $pageRange) {
            $this->_pageRanges[] = clone $pageRange;
        }
    }

}
