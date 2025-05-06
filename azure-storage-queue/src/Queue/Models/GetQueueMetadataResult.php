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
 * @package   MicrosoftAzure\Storage\Queue\Models
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */

namespace MicrosoftAzure\Storage\Queue\Models;

use function is_null;

/**
 * Holds result from calling GetQueueMetadata wrapper
 *
 * @category  Microsoft
 * @package   MicrosoftAzure\Storage\Queue\Models
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */
class GetQueueMetadataResult
{

    private $_approximateMessageCount;

    private $_metadata;

    /**
     * Constructor
     *
     * @param int $approximateMessageCount Approximate number of queue messages.
     * @param array   $metadata                user defined metadata.
     *
     * @internal
     */
    public function __construct(int $approximateMessageCount, array $metadata)
    {
        $this->setApproximateMessageCount($approximateMessageCount);
        $this->setMetadata(is_null($metadata) ? [] : $metadata);
    }

    /**
     * Gets approximate message count.
     *
     */
    public function getApproximateMessageCount(): int
    {
        return $this->_approximateMessageCount;
    }

    /**
     * Sets approximate message count.
     *
     * @param int $approximateMessageCount value to use.
     *
     * @internal
     *
     */
    protected function setApproximateMessageCount(int $approximateMessageCount): void
    {
        $this->_approximateMessageCount = $approximateMessageCount;
    }

    /**
     * Sets metadata.
     *
     */
    public function getMetadata(): array
    {
        return $this->_metadata;
    }

    /**
     * Sets metadata.
     *
     * @param array $metadata value to use.
     *
     * @internal
     *
     */
    protected function setMetadata(array $metadata): void
    {
        $this->_metadata = $metadata;
    }

}
