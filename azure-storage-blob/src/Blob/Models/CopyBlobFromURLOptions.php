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

use function is_array;
use function is_null;

/**
 * optional parameters for CopyBlobOptions wrapper
 *
 * @category  Microsoft
 * @package   MicrosoftAzure\Storage\Blob\Models
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2017 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */
class CopyBlobFromURLOptions extends BlobServiceOptions
{

    use AccessTierTrait;

    private $sourceLeaseId;

    private $sourceAccessConditions;

    private $metadata;

    private $isIncrementalCopy;

    /**
     * Gets source access condition
     *
     * @return AccessCondition[]
     */
    public function getSourceAccessConditions(): array
    {
        return $this->sourceAccessConditions;
    }

    /**
     * Sets source access condition
     *
     * @param array $sourceAccessConditions value to use.
     *
     */
    public function setSourceAccessConditions(array $sourceAccessConditions): void
    {
        if (
            !is_null($sourceAccessConditions) &&
            is_array($sourceAccessConditions)
        ) {
            $this->sourceAccessConditions = $sourceAccessConditions;
        } else {
            $this->sourceAccessConditions = [$sourceAccessConditions];
        }
    }

    /**
     * Gets metadata.
     *
     */
    public function getMetadata(): array
    {
        return $this->metadata;
    }

    /**
     * Sets metadata.
     *
     * @param array $metadata value.
     *
     */
    public function setMetadata(array $metadata): void
    {
        $this->metadata = $metadata;
    }

    /**
     * Gets source lease ID.
     *
     */
    public function getSourceLeaseId(): string
    {
        return $this->sourceLeaseId;
    }

    /**
     * Sets source lease ID.
     *
     * @param string $sourceLeaseId value.
     *
     */
    public function setSourceLeaseId(string $sourceLeaseId): void
    {
        $this->sourceLeaseId = $sourceLeaseId;
    }

    /**
     * Gets isIncrementalCopy.
     *
     */
    public function getIsIncrementalCopy(): bool
    {
        return $this->isIncrementalCopy;
    }

    /**
     * Sets isIncrementalCopy.
     *
     *
     */
    public function setIsIncrementalCopy(bool $isIncrementalCopy): void
    {
        $this->isIncrementalCopy = $isIncrementalCopy;
    }

}
