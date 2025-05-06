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
use MicrosoftAzure\Storage\Common\Internal\Serialization\XmlSerializer;
use MicrosoftAzure\Storage\Common\Internal\Utilities;
use function intval;

/**
 * Holds data for single queue message.
 *
 * @category  Microsoft
 * @package   MicrosoftAzure\Storage\Queue\Models
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */
class QueueMessage
{

    private $messageId;

    private $insertionDate;

    private $expirationDate;

    private $popReceipt;

    private $timeNextVisible;

    private $dequeueCount;

    private $_messageText;

    private static $xmlRootName = 'QueueMessage';

    /**
     * Creates QueueMessage object from parsed XML response of
     * ListMessages.
     *
     * @param array $parsedResponse XML response parsed into array.
     *
     * @internal
     *
     */
    public static function createFromListMessages(array $parsedResponse): QueueMessage
    {
        $timeNextVisible = $parsedResponse['TimeNextVisible'];

        $msg  = self::createFromPeekMessages($parsedResponse);
        $date = Utilities::rfc1123ToDateTime($timeNextVisible);
        $msg->setTimeNextVisible($date);
        $msg->setPopReceipt($parsedResponse['PopReceipt']);

        return $msg;
    }

    /**
     * Creates QueueMessage object from parsed XML response of
     * PeekMessages.
     *
     * @param array $parsedResponse XML response parsed into array.
     *
     * @internal
     *
     */
    public static function createFromPeekMessages(array $parsedResponse): QueueMessage
    {
        $msg            = new QueueMessage();
        $expirationDate = $parsedResponse['ExpirationTime'];
        $insertionDate  = $parsedResponse['InsertionTime'];

        $msg->setDequeueCount(intval($parsedResponse['DequeueCount']));

        $date = Utilities::rfc1123ToDateTime($expirationDate);
        $msg->setExpirationDate($date);

        $date = Utilities::rfc1123ToDateTime($insertionDate);
        $msg->setInsertionDate($date);

        $msg->setMessageId($parsedResponse['MessageId']);
        $msg->setMessageText($parsedResponse['MessageText']);

        return $msg;
    }

    /**
     * Creates QueueMessage object from parsed XML response of
     * createMessage.
     *
     * @param array $parsedResponse XML response parsed into array.
     *
     * @internal
     *
     */
    public static function createFromCreateMessage(array $parsedResponse): QueueMessage
    {
        $msg = new QueueMessage();

        $expirationDate  = $parsedResponse['ExpirationTime'];
        $insertionDate   = $parsedResponse['InsertionTime'];
        $timeNextVisible = $parsedResponse['TimeNextVisible'];

        $date = Utilities::rfc1123ToDateTime($expirationDate);
        $msg->setExpirationDate($date);

        $date = Utilities::rfc1123ToDateTime($insertionDate);
        $msg->setInsertionDate($date);

        $date = Utilities::rfc1123ToDateTime($timeNextVisible);
        $msg->setTimeNextVisible($date);

        $msg->setMessageId($parsedResponse['MessageId']);
        $msg->setPopReceipt($parsedResponse['PopReceipt']);

        return $msg;
    }

    /**
     * Gets message text field.
     *
     */
    public function getMessageText(): string
    {
        return $this->_messageText;
    }

    /**
     * Sets message text field.
     *
     * @param string $messageText message contents.
     *
     */
    public function setMessageText(string $messageText): void
    {
        $this->_messageText = $messageText;
    }

    /**
     * Gets messageId field.
     *
     */
    public function getMessageId(): string
    {
        return $this->messageId;
    }

    /**
     * Sets messageId field.
     *
     * @param string $messageId message contents.
     *
     */
    public function setMessageId(string $messageId): void
    {
        $this->messageId = $messageId;
    }

    /**
     * Gets insertionDate field.
     *
     */
    public function getInsertionDate(): DateTime
    {
        return $this->insertionDate;
    }

    /**
     * Sets insertionDate field.
     *
     * @param \DateTime $insertionDate message contents.
     *
     * @internal
     *
     */
    public function setInsertionDate(DateTime $insertionDate): void
    {
        $this->insertionDate = $insertionDate;
    }

    /**
     * Gets expirationDate field.
     *
     */
    public function getExpirationDate(): DateTime
    {
        return $this->expirationDate;
    }

    /**
     * Sets expirationDate field.
     *
     * @param \DateTime $expirationDate the expiration date of the message.
     *
     */
    public function setExpirationDate(DateTime $expirationDate): void
    {
        $this->expirationDate = $expirationDate;
    }

    /**
     * Gets timeNextVisible field.
     *
     */
    public function getTimeNextVisible(): DateTime
    {
        return $this->timeNextVisible;
    }

    /**
     * Sets timeNextVisible field.
     *
     * @param \DateTime $timeNextVisible next visibile time for the message.
     *
     */
    public function setTimeNextVisible(DateTime $timeNextVisible): void
    {
        $this->timeNextVisible = $timeNextVisible;
    }

    /**
     * Gets popReceipt field.
     *
     */
    public function getPopReceipt(): string
    {
        return $this->popReceipt;
    }

    /**
     * Sets popReceipt field.
     *
     * @param string $popReceipt used when deleting the message.
     *
     */
    public function setPopReceipt(string $popReceipt): void
    {
        $this->popReceipt = $popReceipt;
    }

    /**
     * Gets dequeueCount field.
     *
     */
    public function getDequeueCount(): int
    {
        return $this->dequeueCount;
    }

    /**
     * Sets dequeueCount field.
     *
     * @param int $dequeueCount number of dequeues for that message.
     *
     * @internal
     *
     */
    public function setDequeueCount(int $dequeueCount): void
    {
        $this->dequeueCount = $dequeueCount;
    }

    /**
     * Converts this current object to XML representation.
     *
     * @param XmlSerializer $xmlSerializer The XML serializer.
     *
     * @internal
     *
     */
    public function toXml(XmlSerializer $xmlSerializer): string
    {
        $array      = ['MessageText' => $this->_messageText];
        $properties = [XmlSerializer::ROOT_NAME => self::$xmlRootName];

        return $xmlSerializer->serialize($array, $properties);
    }

}
