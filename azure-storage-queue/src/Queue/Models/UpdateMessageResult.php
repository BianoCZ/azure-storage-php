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

use DateTime;
use MicrosoftAzure\Storage\Common\Internal\Utilities;
use MicrosoftAzure\Storage\Common\Internal\Validate;
use MicrosoftAzure\Storage\Queue\Internal\QueueResources as Resources;

/**
 * Holds results of updateMessage wrapper.
 *
 * @category  Microsoft
 * @package   MicrosoftAzure\Storage\Queue\Models
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */
class UpdateMessageResult
{

    private $_popReceipt;

    private $_timeNextVisible;

    /**
     * Creates an instance with the given response headers.
     *
     * @param  array  $headers The response headers used to create the instance.
     *
     * @internal
     *
     */
    public static function create(array $headers): UpdateMessageResult
    {
        $result = new UpdateMessageResult();
        $result->setPopReceipt(Utilities::tryGetValueInsensitive(
            Resources::X_MS_POPRECEIPT,
            $headers
        ));
        $timeNextVisible = Utilities::tryGetValueInsensitive(
            Resources::X_MS_TIME_NEXT_VISIBLE,
            $headers
        );
        $date   = Utilities::rfc1123ToDateTime($timeNextVisible);
        $result->setTimeNextVisible($date);

        return $result;
    }

    /**
     * Gets timeNextVisible field.
     *
     */
    public function getTimeNextVisible(): DateTime
    {
        return $this->_timeNextVisible;
    }

    /**
     * Sets timeNextVisible field.
     *
     * @param \DateTime $timeNextVisible A UTC date/time value that represents when
     * the message will be visible on the queue.
     *
     * @internal
     *
     */
    protected function setTimeNextVisible(DateTime $timeNextVisible): void
    {
        Validate::isDate($timeNextVisible);

        $this->_timeNextVisible = $timeNextVisible;
    }

    /**
     * Gets popReceipt field.
     *
     */
    public function getPopReceipt(): string
    {
        return $this->_popReceipt;
    }

    /**
     * Sets popReceipt field.
     *
     * @param string $popReceipt The pop receipt of the queue message.
     *
     * @internal
     *
     */
    protected function setPopReceipt(string $popReceipt): void
    {
        Validate::canCastAsString($popReceipt, 'popReceipt');
        $this->_popReceipt = $popReceipt;
    }

}
