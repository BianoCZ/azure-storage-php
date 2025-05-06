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
 * @package   MicrosoftAzure\Storage\Tests\Functional\Queue
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */

namespace MicrosoftAzure\Storage\Tests\Functional\Queue;

use InvalidArgumentException;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Common\Internal\Resources;
use MicrosoftAzure\Storage\Queue\Models\ListMessagesOptions;
use MicrosoftAzure\Storage\Queue\Models\PeekMessagesOptions;
use MicrosoftAzure\Storage\Queue\Models\QueueServiceOptions;
use MicrosoftAzure\Storage\Tests\Framework\TestResources;
use function sprintf;

class QueueServiceFunctionalParameterTest extends FunctionalTestBase
{
    public function testGetServicePropertiesNullOptions(): void
    {
        try {
            $this->restProxy->getServiceProperties(null);
            $this->assertFalse($this->isEmulated(), 'Should fail if and only if in emulator');
        } catch (ServiceException $e) {
            // Expect failure when run this test with emulator, as v1.6 doesn't support this method
            if ($this->isEmulated()) {
                // Properties are not supported in emulator
                $this->assertEquals(TestResources::STATUS_BAD_REQUEST, $e->getCode(), 'getCode');
            } else {
                throw $e;
            }
        }
    }

    public function testSetServicePropertiesNullOptions1(): void
    {
        $serviceProperties = QueueServiceFunctionalTestData::getDefaultServiceProperties();
        try {
            $this->restProxy->setServiceProperties($serviceProperties);
            $this->assertFalse($this->isEmulated(), 'service properties should throw in emulator');
        } catch (ServiceException $e) {
            if ($this->isEmulated()) {
                // Properties are not supported in emulator
                $this->assertEquals(TestResources::STATUS_BAD_REQUEST, $e->getCode(), 'getCode');
            } else {
                throw $e;
            }
        }
    }

    public function testSetServicePropertiesNullOptions2(): void
    {
        $serviceProperties = QueueServiceFunctionalTestData::getDefaultServiceProperties();

        try {
            $this->restProxy->setServiceProperties($serviceProperties, null);
            $this->assertFalse($this->isEmulated(), 'service properties should throw in emulator');
        } catch (ServiceException $e) {
            if ($this->isEmulated()) {
                // Setting is not supported in emulator
                $this->assertEquals(TestResources::STATUS_BAD_REQUEST, $e->getCode(), 'getCode');
            } else {
                throw $e;
            }
        }
    }

    public function testListQueuesNullOptions(): void
    {
        $this->restProxy->listQueues(null);
        $this->assertTrue(true, 'Should just work');
    }

    public function testCreateQueueNullName(): void
    {
        try {
            $this->restProxy->createQueue(null);
            $this->fail('Expect null name to throw');
        } catch (ServiceException) {
            $this->fail('Should not get a service exception');
        } catch (InvalidArgumentException $e) {
            $this->assertEquals(sprintf(Resources::NULL_OR_EMPTY_MSG, 'queueName'), $e->getMessage(), 'Expect error message');
        }
    }

    public function testDeleteQueueNullName(): void
    {
        try {
            $this->restProxy->deleteQueue(null);
            $this->fail('Expect null name to throw');
        } catch (ServiceException) {
            $this->fail('Should not get a service exception');
        } catch (InvalidArgumentException $e) {
            $this->assertEquals(sprintf(Resources::NULL_OR_EMPTY_MSG, 'queueName'), $e->getMessage(), 'Expect error message');
        }
    }

    public function testSetQueueMetadataNullMetadata(): void
    {
        $queue = QueueServiceFunctionalTestData::$testQueueNames[0];
        $this->restProxy->setQueueMetadata($queue, null);
        $this->assertTrue(true, 'Should just work');
    }

    public function testSetQueueMetadataEmptyMetadata(): void
    {
        $queue = QueueServiceFunctionalTestData::$testQueueNames[0];
        $this->restProxy->setQueueMetadata($queue, []);
        $this->assertTrue(true, 'Should just work');
    }

    public function testSetQueueMetadataNullOptions(): void
    {
        $queue = QueueServiceFunctionalTestData::$testQueueNames[0];
        $this->restProxy->setQueueMetadata($queue, [], null);
        $this->assertTrue(true, 'Should just work');
    }

    public function testCreateMessageQueueNull(): void
    {
        $queue = QueueServiceFunctionalTestData::$testQueueNames[0];
        try {
            $this->restProxy->createMessage(null, null);
            $this->fail('Expect null name to throw');
        } catch (ServiceException) {
            $this->fail('Should not get a service exception');
        } catch (InvalidArgumentException $e) {
            $this->assertEquals(sprintf(Resources::NULL_OR_EMPTY_MSG, 'queueName'), $e->getMessage(), 'Expect error message');
        }
        $this->restProxy->clearMessages($queue);
    }

    public function testCreateMessageNull(): void
    {
        $queue = QueueServiceFunctionalTestData::$testQueueNames[0];
        $this->restProxy->createMessage($queue, null);
        $this->restProxy->clearMessages($queue);
        $this->assertTrue(true, 'Should just work');
    }

    public function testCreateMessageBothMessageAndOptionsNull(): void
    {
        $queue = QueueServiceFunctionalTestData::$testQueueNames[0];
        $this->restProxy->createMessage($queue, null, null);
        $this->restProxy->clearMessages($queue);
        $this->assertTrue(true, 'Should just work');
    }

    public function testCreateMessageMessageNull(): void
    {
        $queue = QueueServiceFunctionalTestData::$testQueueNames[0];
        $this->restProxy->createMessage($queue, null, QueueServiceFunctionalTestData::getSimpleCreateMessageOptions());
        $this->restProxy->clearMessages($queue);
        $this->assertTrue(true, 'Should just work');
    }

    public function testCreateMessageOptionsNull(): void
    {
        $queue = QueueServiceFunctionalTestData::$testQueueNames[0];
        $this->restProxy->createMessage($queue, QueueServiceFunctionalTestData::getSimpleMessageText(), null);
        $this->restProxy->clearMessages($queue);
        $this->assertTrue(true, 'Should just work');
    }

    public function testUpdateMessageQueueNull(): void
    {
        $queue = null;
        $messageId = 'abc';
        $popReceipt = 'abc';
        $messageText = 'abc';
        $options = new QueueServiceOptions();
        $visibilityTimeoutInSeconds = 1;

        try {
            $this->restProxy->updateMessage($queue, $messageId, $popReceipt, $messageText, $visibilityTimeoutInSeconds, $options);
            $this->fail('Expect null name to throw');
        } catch (InvalidArgumentException $e) {
            $this->assertEquals(sprintf(Resources::NULL_OR_EMPTY_MSG, 'queueName'), $e->getMessage(), 'Expect error message');
        }
    }

    public function testUpdateMessageQueueEmpty(): void
    {
        $queue = '';
        $messageId = 'abc';
        $popReceipt = 'abc';
        $messageText = 'abc';
        $options = new QueueServiceOptions();
        $visibilityTimeoutInSeconds = 1;

        try {
            $this->restProxy->updateMessage($queue, $messageId, $popReceipt, $messageText, $visibilityTimeoutInSeconds, $options);
            $this->fail('Expect null name to throw');
        } catch (InvalidArgumentException $e) {
            $this->assertEquals(sprintf(Resources::NULL_OR_EMPTY_MSG, 'queueName'), $e->getMessage(), 'Expect error message');
        }
    }

    public function testUpdateMessageMessageIdNull(): void
    {
        $queue = QueueServiceFunctionalTestData::$testQueueNames[0];
        $messageId = null;
        $popReceipt = 'abc';
        $messageText = 'abc';
        $options = new QueueServiceOptions();
        $visibilityTimeoutInSeconds = 1;

        try {
            $this->restProxy->updateMessage($queue, $messageId, $popReceipt, $messageText, $visibilityTimeoutInSeconds, $options);
            $this->fail('Expect null messageId to throw');
        } catch (InvalidArgumentException $e) {
            $this->assertEquals(sprintf(Resources::NULL_OR_EMPTY_MSG, 'messageId'), $e->getMessage(), 'Expect error message');
        }
    }

    public function testUpdateMessageMessageIdEmpty(): void
    {
        $queue = QueueServiceFunctionalTestData::$testQueueNames[0];
        $messageId = '';
        $popReceipt = 'abc';
        $messageText = 'abc';
        $options = new QueueServiceOptions();
        $visibilityTimeoutInSeconds = 1;

        try {
            $this->restProxy->updateMessage($queue, $messageId, $popReceipt, $messageText, $visibilityTimeoutInSeconds, $options);
            $this->fail('Expect null messageId to throw');
        } catch (InvalidArgumentException $e) {
            $this->assertEquals(sprintf(Resources::NULL_OR_EMPTY_MSG, 'messageId'), $e->getMessage(), 'Expect error message');
        }
    }

    public function testUpdateMessagePopReceiptNull(): void
    {
        $queue = QueueServiceFunctionalTestData::$testQueueNames[0];
        $messageId = 'abc';
        $popReceipt = null;
        $messageText = 'abc';
        $options = new QueueServiceOptions();
        $visibilityTimeoutInSeconds = 1;

        try {
            $this->restProxy->updateMessage($queue, $messageId, $popReceipt, $messageText, $visibilityTimeoutInSeconds, $options);
            $this->fail('Expect null popReceipt to throw');
        } catch (InvalidArgumentException $e) {
            $this->assertEquals(sprintf(Resources::NULL_OR_EMPTY_MSG, 'popReceipt'), $e->getMessage(), 'Expect error message');
        }
    }

    public function testUpdateMessagePopReceiptEmpty(): void
    {
        $queue = QueueServiceFunctionalTestData::$testQueueNames[0];
        $messageId = 'abc';
        $popReceipt = '';
        $messageText = 'abc';
        $options = new QueueServiceOptions();
        $visibilityTimeoutInSeconds = 1;

        try {
            $this->restProxy->updateMessage($queue, $messageId, $popReceipt, $messageText, $visibilityTimeoutInSeconds, $options);
            $this->fail('Expect null popReceipt to throw');
        } catch (InvalidArgumentException $e) {
            $this->assertEquals(sprintf(Resources::NULL_OR_EMPTY_MSG, 'popReceipt'), $e->getMessage(), 'Expect error message');
        }
    }

    public function testUpdateMessageMessageTextNull(): void
    {
        $queue = QueueServiceFunctionalTestData::$testQueueNames[0];
        $messageId = 'abc';
        $popReceipt = 'abc';
        $messageText = null;
        $options = new QueueServiceOptions();
        $visibilityTimeoutInSeconds = 1;

        try {
            $this->restProxy->updateMessage($queue, $messageId, $popReceipt, $messageText, $visibilityTimeoutInSeconds, $options);
            $this->fail('Expect bogus message id to throw');
        } catch (ServiceException $e) {
            $this->assertEquals(TestResources::STATUS_BAD_REQUEST, $e->getCode(), 'getCode');
        }
    }

    public function testUpdateMessageMessageTextEmpty(): void
    {
        $queue = QueueServiceFunctionalTestData::$testQueueNames[0];
        $messageId = 'abc';
        $popReceipt = 'abc';
        $messageText = '';
        $options = new QueueServiceOptions();
        $visibilityTimeoutInSeconds = 1;

        try {
            $this->restProxy->updateMessage($queue, $messageId, $popReceipt, $messageText, $visibilityTimeoutInSeconds, $options);
            $this->fail('Expect bogus message id to throw');
        } catch (ServiceException $e) {
            $this->assertEquals(TestResources::STATUS_BAD_REQUEST, $e->getCode(), 'getCode');
        }
    }

    public function testUpdateMessageOptionsNull(): void
    {
        $queue = QueueServiceFunctionalTestData::$testQueueNames[0];
        $messageId = 'abc';
        $popReceipt = 'abc';
        $messageText = 'abc';
        $options = null;
        $visibilityTimeoutInSeconds = 1;

        try {
            $this->restProxy->updateMessage($queue, $messageId, $popReceipt, $messageText, $visibilityTimeoutInSeconds, $options);
            $this->fail('Expect bogus message id to throw');
        } catch (ServiceException $e) {
            $this->assertEquals(TestResources::STATUS_BAD_REQUEST, $e->getCode(), 'getCode');
        }
    }

    public function testUpdateMessageVisibilityTimeout0(): void
    {
        $queue = QueueServiceFunctionalTestData::$testQueueNames[0];
        $messageId = 'abc';
        $popReceipt = 'abc';
        $messageText = 'abc';
        $options = new QueueServiceOptions();
        $visibilityTimeoutInSeconds = 0;

        try {
            $this->restProxy->updateMessage($queue, $messageId, $popReceipt, $messageText, $visibilityTimeoutInSeconds, $options);
            $this->fail('Expect bogus message id to throw');
        } catch (InvalidArgumentException) {
            $this->fail('Should not get an InvalidArgumentException exception');
        } catch (ServiceException $e) {
            $this->assertEquals(TestResources::STATUS_BAD_REQUEST, $e->getCode(), 'getCode');
        }
    }

    public function testUpdateMessageVisibilityTimeoutNull(): void
    {
        $queue = QueueServiceFunctionalTestData::$testQueueNames[0];
        $messageId = 'abc';
        $popReceipt = 'abc';
        $messageText = 'abc';
        $options = new QueueServiceOptions();
        $visibilityTimeoutInSeconds = null;

        try {
            $this->restProxy->updateMessage($queue, $messageId, $popReceipt, $messageText, $visibilityTimeoutInSeconds, $options);
            $this->fail('Expect null visibilityTimeoutInSeconds to throw');
        } catch (InvalidArgumentException $e) {
            $this->assertEquals(sprintf(Resources::NULL_MSG, 'visibilityTimeoutInSeconds'), $e->getMessage(), 'Expect error message');
        }
    }

    public function testDeleteMessageQueueNullNoOptions(): void
    {
        $queue = null;
        $messageId = 'abc';
        $popReceipt = 'abc';

        try {
            $this->restProxy->deleteMessage($queue, $messageId, $popReceipt);
            $this->fail('Expect null queue to throw');
        } catch (InvalidArgumentException $e) {
            $this->assertEquals(sprintf(Resources::NULL_OR_EMPTY_MSG, 'queueName'), $e->getMessage(), 'Expect error message');
        }
    }

    public function testDeleteMessageQueueEmptyNoOptions(): void
    {
        $queue = '';
        $messageId = 'abc';
        $popReceipt = 'abc';

        try {
            $this->restProxy->deleteMessage($queue, $messageId, $popReceipt);
            $this->fail('Expect empty queue to throw');
        } catch (InvalidArgumentException $e) {
            $this->assertEquals(sprintf(Resources::NULL_OR_EMPTY_MSG, 'queueName'), $e->getMessage(), 'Expect error message');
        }
    }

    public function testDeleteMessageQueueNullWithOptions(): void
    {
        $queue = null;
        $messageId = 'abc';
        $popReceipt = 'abc';
        $options = new QueueServiceOptions();

        try {
            $this->restProxy->deleteMessage($queue, $messageId, $popReceipt, $options);
            $this->fail('Expect null queue to throw');
        } catch (InvalidArgumentException $e) {
            $this->assertEquals(sprintf(Resources::NULL_OR_EMPTY_MSG, 'queueName'), $e->getMessage(), 'Expect error message');
        }
    }

    public function testDeleteMessageMessageIdNull(): void
    {
        $queue = 'abc';
        $messageId = null;
        $popReceipt = 'abc';
        $options = new QueueServiceOptions();

        try {
            $this->restProxy->deleteMessage($queue, $messageId, $popReceipt, $options);
            $this->fail('Expect null messageId to throw');
        } catch (InvalidArgumentException $e) {
            $this->assertEquals(sprintf(Resources::NULL_OR_EMPTY_MSG, 'messageId'), $e->getMessage(), 'Expect error message');
        }
    }

    public function testDeleteMessageMessageIdEmpty(): void
    {
        $queue = 'abc';
        $messageId = '';
        $popReceipt = 'abc';
        $options = new QueueServiceOptions();

        try {
            $this->restProxy->deleteMessage($queue, $messageId, $popReceipt, $options);
            $this->fail('Expect empty messageId to throw');
        } catch (InvalidArgumentException $e) {
            $this->assertEquals(sprintf(Resources::NULL_OR_EMPTY_MSG, 'messageId'), $e->getMessage(), 'Expect error message');
        }
    }

    public function testDeleteMessagePopReceiptNull(): void
    {
        $queue = 'abc';
        $messageId = 'abc';
        $popReceipt = null;
        $options = new QueueServiceOptions();

        try {
            $this->restProxy->deleteMessage($queue, $messageId, $popReceipt, $options);
            $this->fail('Expect null popReceipt to throw');
        } catch (InvalidArgumentException $e) {
            $this->assertEquals(sprintf(Resources::NULL_OR_EMPTY_MSG, 'popReceipt'), $e->getMessage(), 'Expect error message');
        }
    }

    public function testDeleteMessagePopReceiptEmpty(): void
    {
        $queue = 'abc';
        $messageId = 'abc';
        $popReceipt = '';
        $options = new QueueServiceOptions();

        try {
            $this->restProxy->deleteMessage($queue, $messageId, $popReceipt, $options);
            $this->fail('Expect empty popReceipt to throw');
        } catch (InvalidArgumentException $e) {
            $this->assertEquals(sprintf(Resources::NULL_OR_EMPTY_MSG, 'popReceipt'), $e->getMessage(), 'Expect error message');
        }
    }

    public function testDeleteMessageOptionsNull(): void
    {
        $queue = 'abc';
        $messageId = 'abc';
        $popReceipt = 'abc';
        $options = null;

        try {
            $this->restProxy->deleteMessage($queue, $messageId, $popReceipt, $options);
            $this->fail('Expect bogus message id to throw');
        } catch (ServiceException $e) {
            $this->assertEquals(TestResources::STATUS_BAD_REQUEST, $e->getCode(), 'getCode');
        }
    }

    public function testListMessagesQueueNullNoOptions(): void
    {
        try {
            $this->restProxy->listMessages(null);
            $this->fail('Expect null name to throw');
        } catch (ServiceException) {
            $this->fail('Should not get a service exception');
        } catch (InvalidArgumentException $e) {
            $this->assertEquals(sprintf(Resources::NULL_OR_EMPTY_MSG, 'queueName'), $e->getMessage(), 'Expect error message');
        }
    }

    public function testListMessagesQueueNullWithOptions(): void
    {
        try {
            $this->restProxy->listMessages(null, new ListMessagesOptions());
            $this->fail('Expect null name to throw');
        } catch (ServiceException) {
            $this->fail('Should not get a service exception');
        } catch (InvalidArgumentException $e) {
            $this->assertEquals(sprintf(Resources::NULL_OR_EMPTY_MSG, 'queueName'), $e->getMessage(), 'Expect error message');
        }
    }

    public function testListMessagesOptionsNull(): void
    {
        try {
            $this->restProxy->listMessages('abc', null);
            $this->fail('Expect bogus queue name to throw');
        } catch (ServiceException $e) {
            $this->assertEquals(TestResources::STATUS_NOT_FOUND, $e->getCode(), 'getCode');
        }
    }

    public function testListMessagesAllNull(): void
    {
        try {
            $this->restProxy->listMessages(null, null);
            $this->fail('Expect null name to throw');
        } catch (ServiceException) {
            $this->fail('Should not get a service exception');
        } catch (InvalidArgumentException $e) {
            $this->assertEquals(sprintf(Resources::NULL_OR_EMPTY_MSG, 'queueName'), $e->getMessage(), 'Expect error message');
        }
    }

    public function testPeekMessagesQueueNullNoOptions(): void
    {
        try {
            $this->restProxy->peekMessages(null);
            $this->fail('Expect null name to throw');
        } catch (ServiceException) {
            $this->fail('Should not get a service exception');
        } catch (InvalidArgumentException $e) {
            $this->assertEquals(sprintf(Resources::NULL_OR_EMPTY_MSG, 'queueName'), $e->getMessage(), 'Expect error message');
        }
    }

    public function testPeekMessagesQueueEmptyNoOptions(): void
    {
        try {
            $this->restProxy->peekMessages('');
            $this->fail('Expect empty name to throw');
        } catch (ServiceException) {
            $this->fail('Should not get a service exception');
        } catch (InvalidArgumentException $e) {
            $this->assertEquals(sprintf(Resources::NULL_OR_EMPTY_MSG, 'queueName'), $e->getMessage(), 'Expect error message');
        }
    }

    public function testPeekMessagesQueueNullWithOptions(): void
    {
        try {
            $this->restProxy->peekMessages(null, new PeekMessagesOptions());
            $this->fail('Expect null name to throw');
        } catch (ServiceException) {
            $this->fail('Should not get a service exception');
        } catch (InvalidArgumentException $e) {
            $this->assertEquals(sprintf(Resources::NULL_OR_EMPTY_MSG, 'queueName'), $e->getMessage(), 'Expect error message');
        }
    }

    public function testPeekMessagesOptionsNull(): void
    {
        try {
            $this->restProxy->peekMessages('abc', null);
            $this->fail('Expect bogus queue name to throw');
        } catch (ServiceException $e) {
            $this->assertEquals(TestResources::STATUS_NOT_FOUND, $e->getCode(), 'getCode');
        }
    }

    public function testPeekMessagesAllNull(): void
    {
        try {
            $this->restProxy->peekMessages(null, null);
            $this->fail('Expect null name to throw');
        } catch (ServiceException) {
            $this->fail('Should not get a service exception');
        } catch (InvalidArgumentException $e) {
            $this->assertEquals(sprintf(Resources::NULL_OR_EMPTY_MSG, 'queueName'), $e->getMessage(), 'Expect error message');
        }
    }

    public function testClearMessagesQueueNullNoOptions(): void
    {
        try {
            $this->restProxy->clearMessages(null);
            $this->fail('Expect null name to throw');
        } catch (ServiceException) {
            $this->fail('Should not get a service exception');
        } catch (InvalidArgumentException $e) {
            $this->assertEquals(sprintf(Resources::NULL_OR_EMPTY_MSG, 'queueName'), $e->getMessage(), 'Expect error message');
        }
    }

    public function testClearMessagesQueueNullWithOptions(): void
    {
        try {
            $this->restProxy->clearMessages(null, new QueueServiceOptions());
            $this->fail('Expect null name to throw');
        } catch (ServiceException) {
            $this->fail('Should not get a service exception');
        } catch (InvalidArgumentException $e) {
            $this->assertEquals(sprintf(Resources::NULL_OR_EMPTY_MSG, 'queueName'), $e->getMessage(), 'Expect error message');
        }
    }

    public function testClearMessagesOptionsNull(): void
    {
        try {
            $this->restProxy->clearMessages('abc', null);
            $this->fail('Expect bogus queue name to throw');
        } catch (ServiceException $e) {
            $this->assertEquals(TestResources::STATUS_NOT_FOUND, $e->getCode(), 'getCode');
        }
    }

    public function testClearMessagesAllNull(): void
    {
        try {
            $this->restProxy->clearMessages(null, null);
            $this->fail('Expect null name to throw');
        } catch (ServiceException) {
            $this->fail('Should not get a service exception');
        } catch (InvalidArgumentException $e) {
            $this->assertEquals(sprintf(Resources::NULL_OR_EMPTY_MSG, 'queueName'), $e->getMessage(), 'Expect error message');
        }
    }

}
