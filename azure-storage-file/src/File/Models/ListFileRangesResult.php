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

use DateTime;
use MicrosoftAzure\Storage\Common\Internal\Utilities;
use MicrosoftAzure\Storage\Common\Internal\Validate;
use MicrosoftAzure\Storage\Common\Models\Range;
use MicrosoftAzure\Storage\File\Internal\FileResources as Resources;
use function array_change_key_case;
use function intval;

/**
 * Holds result of calling ListFileRangesResult wrapper
 *
 * @category  Microsoft
 * @package   MicrosoftAzure\Storage\File\Models
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2017 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */
class ListFileRangesResult
{

    private $lastModified;

    private $etag;

    private $contentLength;

    private $ranges;

    /**
     * Creates ListFileRangesResult object from $parsed response and
     * $headers in array representation
     *
     * @param array $headers HTTP response headers
     * @param array $parsed  parsed response in array format.
     *
     * @internal
     *
     */
    public static function create(array $headers, ?array $parsed = null): ListFileRangesResult
    {
        $result  = new ListFileRangesResult();
        $headers = array_change_key_case($headers);

        $date          = $headers[Resources::LAST_MODIFIED];
        $date          = Utilities::rfc1123ToDateTime($date);
        $fileLength    = intval($headers[Resources::X_MS_CONTENT_LENGTH]);
        $rawRanges = [];
        if (!empty($parsed['Range'])) {
            $rawRanges = Utilities::getArray($parsed['Range']);
        }

        $ranges = [];
        foreach ($rawRanges as $value) {
            $ranges[] = new Range(
                intval($value['Start']),
                intval($value['End'])
            );
        }
        $result->setRanges($ranges);
        $result->setContentLength($fileLength);
        $result->setETag($headers[Resources::ETAG]);
        $result->setLastModified($date);

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
        Validate::canCastAsString($etag, 'etag');
        $this->etag = $etag;
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
    protected function setContentLength(int $contentLength): void
    {
        Validate::isInteger($contentLength, 'contentLength');
        $this->contentLength = $contentLength;
    }

    /**
     * Gets ranges
     *
     */
    public function getRanges(): array
    {
        return $this->ranges;
    }

    /**
     * Sets ranges
     *
     * @param array $ranges ranges to set
     *
     */
    protected function setRanges(array $ranges): void
    {
        $this->ranges = [];
        foreach ($ranges as $range) {
            $this->ranges[] = clone $range;
        }
    }

}
