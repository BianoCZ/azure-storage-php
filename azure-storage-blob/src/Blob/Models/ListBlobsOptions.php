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
use MicrosoftAzure\Storage\Common\MarkerContinuationTokenTrait;

/**
 * Optional parameters for listBlobs API.
 *
 * @category  Microsoft
 * @package   MicrosoftAzure\Storage\Blob\Models
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */
class ListBlobsOptions extends BlobServiceOptions
{

    use MarkerContinuationTokenTrait;

    private $_prefix;

    private $_delimiter;

    private $_maxResults;

    private $_includeMetadata;

    private $_includeSnapshots;

    private $_includeUncommittedBlobs;

    private $_includeCopy;

    private $_includeDeleted;

    /**
     * Gets prefix.
     *
     */
    public function getPrefix(): string
    {
        return $this->_prefix;
    }

    /**
     * Sets prefix.
     *
     * @param string $prefix value.
     *
     */
    public function setPrefix(string $prefix): void
    {
        Validate::canCastAsString($prefix, 'prefix');
        $this->_prefix = $prefix;
    }

    /**
     * Gets delimiter.
     *
     */
    public function getDelimiter(): string
    {
        return $this->_delimiter;
    }

    /**
     * Sets prefix.
     *
     * @param string $delimiter value.
     *
     */
    public function setDelimiter(string $delimiter): void
    {
        Validate::canCastAsString($delimiter, 'delimiter');
        $this->_delimiter = $delimiter;
    }

    /**
     * Gets max results.
     *
     */
    public function getMaxResults(): int
    {
        return $this->_maxResults;
    }

    /**
     * Sets max results.
     *
     * @param int $maxResults value.
     *
     */
    public function setMaxResults(int $maxResults): void
    {
        Validate::isInteger($maxResults, 'maxResults');
        $this->_maxResults = $maxResults;
    }

    /**
     * Indicates if metadata is included or not.
     *
     */
    public function getIncludeMetadata(): bool
    {
        return $this->_includeMetadata;
    }

    /**
     * Sets the include metadata flag.
     *
     * @param bool $includeMetadata value.
     *
     */
    public function setIncludeMetadata(bool $includeMetadata): void
    {
        Validate::isBoolean($includeMetadata);
        $this->_includeMetadata = $includeMetadata;
    }

    /**
     * Indicates if snapshots is included or not.
     *
     */
    public function getIncludeSnapshots(): bool
    {
        return $this->_includeSnapshots;
    }

    /**
     * Sets the include snapshots flag.
     *
     * @param bool $includeSnapshots value.
     *
     */
    public function setIncludeSnapshots(bool $includeSnapshots): void
    {
        Validate::isBoolean($includeSnapshots);
        $this->_includeSnapshots = $includeSnapshots;
    }

    /**
     * Indicates if uncommittedBlobs is included or not.
     *
     */
    public function getIncludeUncommittedBlobs(): bool
    {
        return $this->_includeUncommittedBlobs;
    }

    /**
     * Sets the include uncommittedBlobs flag.
     *
     * @param bool $includeUncommittedBlobs value.
     *
     */
    public function setIncludeUncommittedBlobs(bool $includeUncommittedBlobs): void
    {
        Validate::isBoolean($includeUncommittedBlobs);
        $this->_includeUncommittedBlobs = $includeUncommittedBlobs;
    }

    /**
     * Indicates if copy is included or not.
     *
     */
    public function getIncludeCopy(): bool
    {
        return $this->_includeCopy;
    }

    /**
     * Sets the include copy flag.
     *
     * @param bool $includeCopy value.
     *
     */
    public function setIncludeCopy(bool $includeCopy): void
    {
        Validate::isBoolean($includeCopy);
        $this->_includeCopy = $includeCopy;
    }

    /**
     * Indicates if deleted is included or not.
     *
     */
    public function getIncludeDeleted(): bool
    {
        return $this->_includeDeleted;
    }

    /**
     * Sets the include deleted flag.
     *
     * @param bool $includeDeleted value.
     *
     */
    public function setIncludeDeleted(bool $includeDeleted): void
    {
        Validate::isBoolean($includeDeleted);
        $this->_includeDeleted = $includeDeleted;
    }

}
