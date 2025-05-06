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
 * @package   MicrosoftAzure\Storage\Queue\Internal
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */

namespace MicrosoftAzure\Storage\Queue\Internal;

use GuzzleHttp\Promise\PromiseInterface;
use MicrosoftAzure\Storage\Common\Models\GetServicePropertiesResult;
use MicrosoftAzure\Storage\Common\Models\GetServiceStatsResult;
use MicrosoftAzure\Storage\Common\Models\ServiceOptions;
use MicrosoftAzure\Storage\Common\Models\ServiceProperties;
use MicrosoftAzure\Storage\Queue\Models as QueueModels;

/**
 * This interface has all REST APIs provided by Windows Azure for queue service
 *
 * @ignore
 * @category  Microsoft
 * @package   MicrosoftAzure\Storage\Queue\Internal
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 * @see       http://msdn.microsoft.com/en-us/library/windowsazure/dd179363.aspx
 */
interface IQueue
{
    /**
     * Gets the properties of the service.
     *
     * @param ServiceOptions $options The optional parameters.
     *
     */
    public function getServiceProperties(
        ?ServiceOptions $options = null
    ): GetServicePropertiesResult;

    /**
     * Creates promise to get the properties of the service.
     *
     * @param ServiceOptions $options The optional parameters.
     *
     */
    public function getServicePropertiesAsync(
        ?ServiceOptions $options = null
    ): PromiseInterface;

    /**
     * Sets the properties of the service.
     *
     * It's recommended to use getServiceProperties, alter the returned object and
     * then use setServiceProperties with this altered object.
     *
     * @param ServiceProperties $serviceProperties The new service properties.
     * @param ServiceOptions    $options           The optional parameters.
     *
     */
    public function setServiceProperties(
        ServiceProperties $serviceProperties,
        ?ServiceOptions $options = null
    ): void;

    /**
     * Creates promise to set the properties of the service.
     *
     * It's recommended to use getServiceProperties, alter the returned object and
     * then use setServiceProperties with this altered object.
     *
     * @param ServiceProperties $serviceProperties The new service properties.
     * @param ServiceOptions    $options           The optional parameters.
     *
     */
    public function setServicePropertiesAsync(
        ServiceProperties $serviceProperties,
        ?ServiceOptions $options = null
    ): PromiseInterface;

    /**
     * Retieves statistics related to replication for the service. The operation
     * will only be sent to secondary location endpoint.
     *
     * @param  ServiceOptions|null $options The options this operation sends with.
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/get-queue-service-stats
     */
    public function getServiceStats(?ServiceOptions $options = null): GetServiceStatsResult;

    /**
     * Creates promise that retrieves statistics related to replication for the
     * service. The operation will only be sent to secondary location endpoint.
     *
     * @param  ServiceOptions|null $options The options this operation sends with.
     *
     * @see  https://docs.microsoft.com/en-us/rest/api/storageservices/get-queue-service-stats
     */
    public function getServiceStatsAsync(?ServiceOptions $options = null): PromiseInterface;

    /**
     * Creates a new queue under the storage account.
     *
     * @param string                         $queueName The queue name.
     * @param QueueModels\CreateQueueOptions $options   The optional queue create options.
     *
     */
    public function createQueue(
        string $queueName,
        ?QueueModels\CreateQueueOptions $options = null
    ): void;

    /**
     * Creates promise to create a new queue under the storage account.
     *
     * @param string                     $queueName The queue name.
     * @param QueueModels\CreateQueueOptions  $options   The Optional parameters.
     *
     */
    public function createQueueAsync(
        string $queueName,
        ?QueueModels\CreateQueueOptions $options = null
    ): PromiseInterface;

    /**
     * Deletes a queue.
     *
     * @param string                          $queueName The queue name.
     * @param QueueModels\QueueServiceOptions $options   The optional parameters.
     *
     */
    public function deleteQueue(
        string $queueName,
        QueueModels\QueueServiceOptions $options
    ): void;

    /**
     * Creates promise to delete a queue.
     *
     * @param string                          $queueName The queue name.
     * @param QueueModels\QueueServiceOptions $options   The optional parameters.
     *
     */
    public function deleteQueueAsync(
        string $queueName,
        ?QueueModels\QueueServiceOptions $options = null
    ): PromiseInterface;

    /**
     * Lists all queues in the storage account.
     *
     * @param QueueModels\ListQueuesOptions $options The optional parameters.
     *
     */
    public function listQueues(?QueueModels\ListQueuesOptions $options = null): QueueModels\ListQueuesResult;

    /**
     * Creates promise to list all queues in the storage account.
     *
     * @param QueueModels\ListQueuesOptions $options The optional list queue options.
     *
     */
    public function listQueuesAsync(?QueueModels\ListQueuesOptions $options = null): PromiseInterface;

    /**
     * Returns queue properties, including user-defined metadata.
     *
     * @param string                          $queueName The queue name.
     * @param QueueModels\QueueServiceOptions $options   The optional parameters.
     *
     */
    public function getQueueMetadata(
        string $queueName,
        ?QueueModels\QueueServiceOptions $options = null
    ): QueueModels\GetQueueMetadataResult;

    /**
     * Creates promise to return queue properties, including user-defined metadata.
     *
     * @param string                          $queueName The queue name.
     * @param QueueModels\QueueServiceOptions $options   The optional parameters.
     *
     */
    public function getQueueMetadataAsync(
        string $queueName,
        ?QueueModels\QueueServiceOptions $options = null
    ): PromiseInterface;

    /**
     * Sets user-defined metadata on the queue. To delete queue metadata, call
     * this API without specifying any metadata in $metadata.
     *
     * @param string                          $queueName The queue name.
     * @param array                           $metadata  The metadata array.
     * @param QueueModels\QueueServiceOptions $options   The optional parameters.
     *
     */
    public function setQueueMetadata(
        string $queueName,
        ?array $metadata = null,
        ?QueueModels\QueueServiceOptions $options = null
    ): void;

    /**
     * Creates promise to set user-defined metadata on the queue. To delete
     * queue metadata, call this API without specifying any metadata in $metadata.
     *
     * @param string                          $queueName The queue name.
     * @param array                           $metadata  The metadata array.
     * @param QueueModels\QueueServiceOptions $options   The optional parameters.
     *
     */
    public function setQueueMetadataAsync(
        string $queueName,
        ?array $metadata = null,
        ?QueueModels\QueueServiceOptions $options = null
    ): PromiseInterface;

    /**
     * Adds a message to the queue and optionally sets a visibility timeout
     * for the message.
     *
     * @param string                           $queueName   The queue name.
     * @param string                           $messageText The message contents.
     * @param QueueModels\CreateMessageOptions $options     The optional parameters.
     *
     */
    public function createMessage(
        string $queueName,
        string $messageText,
        ?QueueModels\CreateMessageOptions $options = null
    ): QueueModels\CreateMessageResult;

    /**
     * Creates promise to add a message to the queue and optionally sets a
     * visibility timeout for the message.
     *
     * @param string                           $queueName   The name of the queue.
     * @param string                           $messageText The message contents.
     * @param QueueModels\CreateMessageOptions $options     The optional
     *                                                      parameters.
     *
     */
    public function createMessageAsync(
        string $queueName,
        string $messageText,
        ?QueueModels\CreateMessageOptions $options = null
    ): PromiseInterface;

    /**
     * Updates the visibility timeout of a message and/or the message contents.
     *
     * @param string              $queueName                  The queue name.
     * @param string              $messageId                  The id of the message.
     * @param string              $popReceipt                 The valid pop receipt
     * value returned from an earlier call to the Get Messages or Update Message
     * operation.
     * @param string              $messageText                The message contents.
     * @param int                 $visibilityTimeoutInSeconds Specifies the new
     * visibility timeout value, in seconds, relative to server time.
     * The new value must be larger than or equal to 0, and cannot be larger
     * than 7 days. The visibility timeout of a message cannot be set to a value
     * later than the expiry time. A message can be updated until it has been
     * deleted or has expired.
     * @param QueueModels\QueueServiceOptions $options The optional parameters.
     *
     */
    public function updateMessage(
        string $queueName,
        string $messageId,
        string $popReceipt,
        string $messageText,
        int $visibilityTimeoutInSeconds,
        ?QueueModels\QueueServiceOptions $options = null
    ): QueueModels\UpdateMessageResult;

    /**
     * Creates promise to update the visibility timeout of a message and/or the
     * message contents.
     *
     * @param string              $queueName                  The queue name.
     * @param string              $messageId                  The id of the message.
     * @param string              $popReceipt                 The valid pop receipt
     * value returned from an earlier call to the Get Messages or Update Message
     * operation.
     * @param string              $messageText                The message contents.
     * @param int                 $visibilityTimeoutInSeconds Specifies the new
     * visibility timeout value, in seconds, relative to server time.
     * The new value must be larger than or equal to 0, and cannot be larger
     * than 7 days. The visibility timeout of a message cannot be set to a value
     * later than the expiry time. A message can be updated until it has been
     * deleted or has expired.
     * @param QueueModels\QueueServiceOptions $options        The optional
     * parameters.
     *
     */
    public function updateMessageAsync(
        string $queueName,
        string $messageId,
        string $popReceipt,
        string $messageText,
        int $visibilityTimeoutInSeconds,
        ?QueueModels\QueueServiceOptions $options = null
    ): PromiseInterface;

    /**
     * Deletes a specified message from the queue.
     *
     * @param string                          $queueName  The queue name.
     * @param string                          $messageId  The id of the message.
     * @param string                          $popReceipt The valid pop receipt
     *                                                    value returned
     *                                                    from an earlier call to
     *                                                    the Get Messages or
     *                                                    update Message operation.
     * @param QueueModels\QueueServiceOptions $options    The optional parameters.
     *
     */
    public function deleteMessage(
        string $queueName,
        string $messageId,
        string $popReceipt,
        ?QueueModels\QueueServiceOptions $options = null
    ): void;

    /**
     * Creates promise to delete a specified message from the queue.
     *
     * @param string                          $queueName  The name of the queue.
     * @param string                          $messageId  The id of the message.
     * @param string                          $popReceipt The valid pop receipt
     *                                                    value returned
     *                                                    from an earlier call to
     *                                                    the Get Messages or
     *                                                    update Message operation.
     * @param QueueModels\QueueServiceOptions $options    The optional parameters.
     *
     */
    public function deleteMessageAsync(
        string $queueName,
        string $messageId,
        string $popReceipt,
        ?QueueModels\QueueServiceOptions $options = null
    ): PromiseInterface;

    /**
     * Lists all messages in the queue.
     *
     * @param string                          $queueName The queue name.
     * @param QueueModels\ListMessagesOptions $options   The optional parameters.
     *
     */
    public function listMessages(
        string $queueName,
        ?QueueModels\ListMessagesOptions $options = null
    ): QueueModels\ListMessagesResult;

    /**
     * Creates promise to list all messages in the queue.
     *
     * @param string              $queueName The queue name.
     * @param QueueModels\ListMessagesOptions $options   The optional parameters.
     *
     */
    public function listMessagesAsync(
        string $queueName,
        ?QueueModels\ListMessagesOptions $options = null
    ): PromiseInterface;

    /**
     * Retrieves a message from the front of the queue, without changing
     * the message visibility.
     *
     * @param string                          $queueName The queue name.
     * @param QueueModels\PeekMessagesOptions $options   The optional parameters.
     *
     */
    public function peekMessages(
        string $queueName,
        ?QueueModels\PeekMessagesOptions $options = null
    ): QueueModels\PeekMessagesResult;

    /**
     * Creates promise to retrieve a message from the front of the queue,
     * without changing the message visibility.
     *
     * @param string                          $queueName The queue name.
     * @param QueueModels\PeekMessagesOptions $options   The optional parameters.
     *
     */
    public function peekMessagesAsync(
        string $queueName,
        ?QueueModels\PeekMessagesOptions $options = null
    ): PromiseInterface;

    /**
     * Clears all messages from the queue.
     *
     * @param string                          $queueName The queue name.
     * @param QueueModels\QueueServiceOptions $options   The optional parameters.
     *
     */
    public function clearMessages(
        string $queueName,
        ?QueueModels\QueueServiceOptions $options = null
    ): QueueModels\PeekMessagesResult;

    /**
     * Creates promise to clear all messages from the queue.
     *
     * If a queue contains a large number of messages, Clear Messages may time out
     * before all messages have been deleted. In this case the Queue service will
     * return status code 500 (Internal Server Error), with the additional error
     * code OperationTimedOut. If the operation times out, the client should
     * continue to retry Clear Messages until it succeeds, to ensure that all
     * messages have been deleted.
     *
     * @param string                          $queueName The name of the queue.
     * @param QueueModels\QueueServiceOptions $options   The optional parameters.
     *
     */
    public function clearMessagesAsync(
        string $queueName,
        ?QueueModels\QueueServiceOptions $options = null
    ): PromiseInterface;

    /**
     * Gets the access control list (ACL)
     *
     * @param string                          $queue   The queue name.
     * @param QueueModels\QueueServiceOptions $options The optional parameters.
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/fileservices/get-queue-acl
     */
    public function getQueueAcl(
        string $queue,
        ?QueueModels\QueueServiceOptions $options = null
    ): QueueModels\QueueACL;

    /**
     * Creates the promise to gets the access control list (ACL)
     *
     * @param string                          $queue   The queue name.
     * @param QueueModels\QueueServiceOptions $options The optional parameters.
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/fileservices/get-queue-acl
     */
    public function getQueueAclAsync(
        string $queue,
        ?QueueModels\QueueServiceOptions $options = null
    ): PromiseInterface;

    /**
     * Sets the ACL.
     *
     * @param string                          $queue   name
     * @param QueueModels\QueueACL            $acl     access control list
     * @param QueueModels\QueueServiceOptions $options optional parameters
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/fileservices/set-queue-acl
     */
    public function setQueueAcl(
        string $queue,
        QueueModels\QueueACL $acl,
        ?QueueModels\QueueServiceOptions $options = null
    ): void;

    /**
     * Creates promise to set the ACL
     *
     * @param string                     $queue   name
     * @param QueueModels\QueueACL            $acl     access control list
     * @param QueueModels\QueueServiceOptions $options optional parameters
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/fileservices/set-queue-acl
     */
    public function setQueueAclAsync(
        string $queue,
        QueueModels\QueueACL $acl,
        ?QueueModels\QueueServiceOptions $options = null
    ): PromiseInterface;

}
