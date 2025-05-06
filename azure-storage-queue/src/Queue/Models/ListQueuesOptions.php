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

use MicrosoftAzure\Storage\Common\Internal\Validate;
use MicrosoftAzure\Storage\Common\MarkerContinuationTokenTrait;

/**
 * Options for listQueues API.
 *
 * @category  Microsoft
 * @package   MicrosoftAzure\Storage\Queue\Models
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */
class ListQueuesOptions extends QueueServiceOptions
{

    use MarkerContinuationTokenTrait;

    private $_prefix;

    private $_maxResults;

    private $_includeMetadata;

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
     * Gets max results.
     *
     */
    public function getMaxResults(): string
    {
        return $this->_maxResults;
    }

    /**
     * Sets max results.
     *
     * @param string $maxResults value.
     *
     */
    public function setMaxResults(string $maxResults): void
    {
        Validate::canCastAsString($maxResults, 'maxResults');
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

}
