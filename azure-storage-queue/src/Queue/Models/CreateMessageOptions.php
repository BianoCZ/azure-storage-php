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

/**
 * Holds optional parameters for createMessage wrapper.
 *
 * @category  Microsoft
 * @package   MicrosoftAzure\Storage\Queue\Models
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */
class CreateMessageOptions extends QueueServiceOptions
{

    private $_visibilityTimeoutInSeconds;

    private $_timeToLiveInSeconds;

    /**
     * Gets visibilityTimeoutInSeconds field.
     *
     */
    public function getVisibilityTimeoutInSeconds(): int
    {
        return $this->_visibilityTimeoutInSeconds;
    }

    /**
     * Sets visibilityTimeoutInSeconds field.
     *
     * @param int $visibilityTimeoutInSeconds value to use.
     *
     */
    public function setVisibilityTimeoutInSeconds(int $visibilityTimeoutInSeconds): void
    {
        $this->_visibilityTimeoutInSeconds = $visibilityTimeoutInSeconds;
    }

    /**
     * Gets timeToLiveInSeconds field.
     *
     */
    public function getTimeToLiveInSeconds(): int
    {
        return $this->_timeToLiveInSeconds;
    }

    /**
     * Sets timeToLiveInSeconds field.
     *
     * @param int $timeToLiveInSeconds value to use.
     *
     */
    public function setTimeToLiveInSeconds(int $timeToLiveInSeconds): void
    {
        $this->_timeToLiveInSeconds = $timeToLiveInSeconds;
    }

}
