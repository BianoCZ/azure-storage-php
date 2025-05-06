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
 * @package   MicrosoftAzure\Storage\Blob\Internal
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */

namespace MicrosoftAzure\Storage\Blob\Internal;

use GuzzleHttp\Promise\PromiseInterface;
use MicrosoftAzure\Storage\Blob\Models as BlobModels;
use MicrosoftAzure\Storage\Blob\Models\BreakLeaseResult;
use MicrosoftAzure\Storage\Common\Models\GetServicePropertiesResult;
use MicrosoftAzure\Storage\Common\Models\GetServiceStatsResult;
use MicrosoftAzure\Storage\Common\Models\Range;
use MicrosoftAzure\Storage\Common\Models\ServiceOptions;
use MicrosoftAzure\Storage\Common\Models\ServiceProperties;
use Psr\Http\Message\StreamInterface;

/**
 * This interface has all REST APIs provided by Windows Azure for Blob service.
 *
 * @ignore
 * @category  Microsoft
 * @package   MicrosoftAzure\Storage\Blob\Internal
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 * @see       http://msdn.microsoft.com/en-us/library/windowsazure/dd135733.aspx
 */
interface IBlob
{
    /**
     * Gets the properties of the service.
     *
     * @param ServiceOptions $options optional service options.
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/hh452239.aspx
     */
    public function getServiceProperties(?ServiceOptions $options = null): GetServicePropertiesResult;

    /**
     * Creates promise to get the properties of the service.
     *
     * @param ServiceOptions $options The optional parameters.
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/hh452239.aspx
     */
    public function getServicePropertiesAsync(?ServiceOptions $options = null): PromiseInterface;

    /**
     * Sets the properties of the service.
     *
     * @param ServiceProperties           $serviceProperties new service properties
     * @param ServiceOptions $options           optional parameters
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/hh452235.aspx
     */
    public function setServiceProperties(
        ServiceProperties $serviceProperties,
        ?ServiceOptions $options = null
    ): void;

    /**
     * Retieves statistics related to replication for the service. The operation
     * will only be sent to secondary location endpoint.
     *
     * @param  ServiceOptions|null $options The options this operation sends with.
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/get-blob-service-stats
     */
    public function getServiceStats(?ServiceOptions $options = null): GetServiceStatsResult;

    /**
     * Creates promise that retrieves statistics related to replication for the
     * service. The operation will only be sent to secondary location endpoint.
     *
     * @param  ServiceOptions|null $options The options this operation sends with.
     *
     * @see  https://docs.microsoft.com/en-us/rest/api/storageservices/get-blob-service-stats
     */
    public function getServiceStatsAsync(?ServiceOptions $options = null): PromiseInterface;

    /**
     * Creates the promise to set the properties of the service.
     *
     * It's recommended to use getServiceProperties, alter the returned object and
     * then use setServiceProperties with this altered object.
     *
     * @param ServiceProperties           $serviceProperties new service properties.
     * @param ServiceOptions $options           optional parameters
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/hh452235.aspx
     */
    public function setServicePropertiesAsync(
        ServiceProperties $serviceProperties,
        ?ServiceOptions $options = null
    ): PromiseInterface;

    /**
     * Lists all of the containers in the given storage account.
     *
     * @param BlobModels\ListContainersOptions $options optional parameters
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/dd179352.aspx
     */
    public function listContainers(?BlobModels\ListContainersOptions $options = null): BlobModels\ListContainersResult;

    /**
     * Create a promise for lists all of the containers in the given
     * storage account.
     *
     * @param  BlobModels\ListContainersOptions $options The optional parameters.
     *
     */
    public function listContainersAsync(
        ?BlobModels\ListContainersOptions $options = null
    ): PromiseInterface;

    /**
     * Creates a new container in the given storage account.
     *
     * @param string                            $container name
     * @param BlobModels\CreateContainerOptions $options   optional parameters
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/dd179468.aspx
     */
    public function createContainer(
        string $container,
        ?BlobModels\CreateContainerOptions $options = null
    ): void;

    /**
     * Creates a new container in the given storage account.
     *
     * @param string                            $container The container name.
     * @param BlobModels\CreateContainerOptions $options   The optional parameters.
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/dd179468.aspx
     */
    public function createContainerAsync(
        string $container,
        ?BlobModels\CreateContainerOptions $options = null
    ): PromiseInterface;

    /**
     * Creates a new container in the given storage account.
     *
     * @param string                            $container name
     * @param BlobModels\BlobServiceOptions     $options   optional parameters
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/dd179408.aspx
     */
    public function deleteContainer(
        string $container,
        ?BlobModels\BlobServiceOptions $options = null
    ): void;

    /**
     * Create a promise for deleting a container.
     *
     * @param  string                             $container name of the container
     * @param  BlobModels\BlobServiceOptions      $options   optional parameters
     *
     */
    public function deleteContainerAsync(
        string $container,
        ?BlobModels\BlobServiceOptions $options = null
    ): PromiseInterface;

    /**
     * Returns all properties and metadata on the container.
     *
     * @param string                        $container name
     * @param BlobModels\BlobServiceOptions $options   optional parameters
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/dd179370.aspx
     */
    public function getContainerProperties(
        string $container,
        ?BlobModels\BlobServiceOptions $options = null
    ): BlobModels\GetContainerPropertiesResult;

    /**
     * Create promise to return all properties and metadata on the container.
     *
     * @param string                        $container name
     * @param BlobModels\BlobServiceOptions $options   optional parameters
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/dd179370.aspx
     */
    public function getContainerPropertiesAsync(
        string $container,
        ?BlobModels\BlobServiceOptions $options = null
    ): PromiseInterface;

    /**
     * Returns only user-defined metadata for the specified container.
     *
     * @param string                        $container name
     * @param BlobModels\BlobServiceOptions $options   optional parameters
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/ee691976.aspx
     */
    public function getContainerMetadata(
        string $container,
        ?BlobModels\BlobServiceOptions $options = null
    ): BlobModels\GetContainerPropertiesResult;

    /**
     * Create promise to return only user-defined metadata for the specified
     * container.
     *
     * @param string                        $container name
     * @param BlobModels\BlobServiceOptions $options   optional parameters
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/ee691976.aspx
     */
    public function getContainerMetadataAsync(
        string $container,
        ?BlobModels\BlobServiceOptions $options = null
    ): PromiseInterface;

    /**
     * Gets the access control list (ACL) and any container-level access policies
     * for the container.
     *
     * @param string                        $container name
     * @param BlobModels\BlobServiceOptions $options   optional parameters
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/dd179469.aspx
     */
    public function getContainerAcl(
        string $container,
        ?BlobModels\BlobServiceOptions $options = null
    ): BlobModels\GetContainerACLResult;

    /**
     * Creates the promise to get the access control list (ACL) and any
     * container-level access policies for the container.
     *
     * @param string                        $container The container name.
     * @param BlobModels\BlobServiceOptions $options   The optional parameters.
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/dd179469.aspx
     */
    public function getContainerAclAsync(
        string $container,
        ?BlobModels\BlobServiceOptions $options = null
    ): PromiseInterface;

    /**
     * Sets the ACL and any container-level access policies for the container.
     *
     * @param string                        $container name
     * @param BlobModels\ContainerACL       $acl       access control list for container
     * @param BlobModels\BlobServiceOptions $options   optional parameters
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/dd179391.aspx
     */
    public function setContainerAcl(
        string $container,
        BlobModels\ContainerACL $acl,
        ?BlobModels\BlobServiceOptions $options = null
    ): void;

    /**
     * Creates promise to set the ACL and any container-level access policies
     * for the container.
     *
     * @param string                        $container name
     * @param BlobModels\ContainerACL       $acl       access control list for container
     * @param BlobModels\BlobServiceOptions $options   optional parameters
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/dd179391.aspx
     */
    public function setContainerAclAsync(
        string $container,
        BlobModels\ContainerACL $acl,
        ?BlobModels\BlobServiceOptions $options = null
    ): PromiseInterface;

    /**
     * Sets metadata headers on the container.
     *
     * @param string                        $container name
     * @param array                         $metadata  metadata key/value pair.
     * @param BlobModels\BlobServiceOptions $options   optional parameters
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/dd179362.aspx
     */
    public function setContainerMetadata(
        string $container,
        array $metadata,
        ?BlobModels\BlobServiceOptions $options = null
    ): void;

    /**
     * Sets metadata headers on the container.
     *
     * @param string                        $container name
     * @param array                         $metadata  metadata key/value pair.
     * @param BlobModels\BlobServiceOptions $options   optional parameters
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/dd179362.aspx
     */
    public function setContainerMetadataAsync(
        string $container,
        array $metadata,
        ?BlobModels\BlobServiceOptions $options = null
    ): PromiseInterface;

    /**
     * Lists all of the blobs in the given container.
     *
     * @param string                      $container name
     * @param BlobModels\ListBlobsOptions $options   optional parameters
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/dd135734.aspx
     */
    public function listBlobs(
        string $container,
        ?BlobModels\ListBlobsOptions $options = null
    ): BlobModels\ListBlobsResult;

    /**
     * Creates promise to list all of the blobs in the given container.
     *
     * @param string                      $container The container name.
     * @param BlobModels\ListBlobsOptions $options   The optional parameters.
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/dd135734.aspx
     */
    public function listBlobsAsync(
        string $container,
        ?BlobModels\ListBlobsOptions $options = null
    ): PromiseInterface;

    /**
     * Creates a new page blob. Note that calling createPageBlob to create a page
     * blob only initializes the blob.
     * To add content to a page blob, call createBlobPages method.
     *
     * @param string                       $container name of the container
     * @param string                       $blob      name of the blob
     * @param int                          $length    specifies the maximum size
     * for the page blob, up to 1 TB. The page blob size must be aligned to
     * a 512-byte boundary.
     * @param BlobModels\CreatePageBlobOptions $options   optional parameters
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/dd179451.aspx
     */
    public function createPageBlob(
        string $container,
        string $blob,
        int $length,
        ?BlobModels\CreatePageBlobOptions $options = null
    ): BlobModels\PutBlobResult;

    /**
     * Creates promise to create a new page blob. Note that calling
     * createPageBlob to create a page blob only initializes the blob.
     * To add content to a page blob, call createBlobPages method.
     *
     * @param string                       $container The container name.
     * @param string                       $blob      The blob name.
     * @param int                      $length    Specifies the maximum size
     *                                                for the page blob, up to
     *                                                1 TB. The page blob size
     *                                                must be aligned to a
     *                                                512-byte boundary.
     * @param BlobModels\CreatePageBlobOptions $options   The optional parameters.
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/dd179451.aspx
     */
    public function createPageBlobAsync(
        string $container,
        string $blob,
        int $length,
        ?BlobModels\CreatePageBlobOptions $options = null
    ): PromiseInterface;

    /**
     * Create a new append blob.
     * If the blob already exists on the service, it will be overwritten.
     *
     * @param string                   $container The container name.
     * @param string                   $blob      The blob name.
     * @param BlobModels\CreateBlobOptions $options   The optional parameters.
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/dd179451.aspx
     */
    public function createAppendBlob(
        string $container,
        string $blob,
        ?BlobModels\CreateBlobOptions $options = null
    ): BlobModels\PutBlobResult;

    /**
     * Creates promise to create a new append blob.
     * If the blob already exists on the service, it will be overwritten.
     *
     * @param string                   $container The container name.
     * @param string                   $blob      The blob name.
     * @param BlobModels\CreateBlobOptions $options   The optional parameters.
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/dd179451.aspx
     */
    public function createAppendBlobAsync(
        string $container,
        string $blob,
        ?BlobModels\CreateBlobOptions $options = null
    ): PromiseInterface;

    /**
     * Creates a new block blob or updates the content of an existing block blob.
     * Updating an existing block blob overwrites any existing metadata on the blob.
     * Partial updates are not supported with createBlockBlob; the content of the
     * existing blob is overwritten with the content of the new blob. To perform a
     * partial update of the content of a block blob, use the createBlockList method.
     *
     * @param string                            $container name of the container
     * @param string                            $blob      name of the blob
     * @param string|resource|StreamInterface   $content   content of the blob
     * @param BlobModels\CreateBlockBlobOptions $options   optional parameters
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/dd179451.aspx
     */
    public function createBlockBlob(
        string $container,
        string $blob,
        $content,
        ?BlobModels\CreateBlockBlobOptions $options = null
    ): BlobModels\PutBlobResult;

    /**
     * Creates a promise to create a new block blob or updates the content of
     * an existing block blob.
     *
     * Updating an existing block blob overwrites any existing metadata on the blob.
     * Partial updates are not supported with createBlockBlob the content of the
     * existing blob is overwritten with the content of the new blob. To perform a
     * partial update of the content of a block blob, use the createBlockList
     * method.
     *
     * @param string                             $container The name of the container.
     * @param string                             $blob      The name of the blob.
     * @param string|resource|StreamInterface    $content   The content of the blob.
     * @param BlobModels\CreateBlockBlobOptions  $options   The optional parameters.
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/dd179451.aspx
     */
    public function createBlockBlobAsync(
        string $container,
        string $blob,
        $content,
        ?BlobModels\CreateBlockBlobOptions $options = null
    ): PromiseInterface;

    /**
     * Create a new page blob and upload the content to the page blob.
     *
     * @param string                          $container The name of the container.
     * @param string                          $blob      The name of the blob.
     * @param int                             $length    The length of the blob.
     * @param string|resource|StreamInterface $content   The content of the blob.
     * @param BlobModels\CreatePageBlobFromContentOptions
     *                                        $options   The optional parameters.
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/fileservices/get-blob-properties
     */
    public function createPageBlobFromContent(
        string $container,
        string $blob,
        int $length,
        $content,
        ?BlobModels\CreatePageBlobFromContentOptions $options = null
    ): BlobModels\GetBlobPropertiesResult;

    /**
     * Creates a promise to create a new page blob and upload the content
     * to the page blob.
     *
     * @param string                          $container The name of the container.
     * @param string                          $blob      The name of the blob.
     * @param int                             $length    The length of the blob.
     * @param string|resource|StreamInterface $content   The content of the blob.
     * @param BlobModels\CreatePageBlobFromContentOptions
     *                                        $options   The optional parameters.
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/fileservices/get-blob-properties
     */
    public function createPageBlobFromContentAsync(
        string $container,
        string $blob,
        int $length,
        $content,
        ?BlobModels\CreatePageBlobFromContentOptions $options = null
    ): PromiseInterface;

    /**
     * Clears a range of pages from the blob.
     *
     * @param string                            $container name of the container
     * @param string                            $blob      name of the blob
     * @param Range                             $range     Can be up to the value
     * of the blob's full size.
     * @param BlobModels\CreateBlobPagesOptions $options   optional parameters
     *
     * @return BlobModels\CreateBlobPagesResult.
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/ee691975.aspx
     */
    public function clearBlobPages(
        string $container,
        string $blob,
        Range $range,
        ?BlobModels\CreateBlobPagesOptions $options = null
    ): BlobModels\CreateBlobPagesResult;

    /**
     * Creates promise to clear a range of pages from the blob.
     *
     * @param string                            $container name of the container
     * @param string                            $blob      name of the blob
     * @param Range                             $range     Can be up to the value
     *                                                     of the blob's full size.
     *                                                     Note that ranges must be
     *                                                     aligned to 512 (0-511,
     *                                                     512-1023)
     * @param BlobModels\CreateBlobPagesOptions $options   optional parameters
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/ee691975.aspx
     */
    public function clearBlobPagesAsync(
        string $container,
        string $blob,
        Range $range,
        ?BlobModels\CreateBlobPagesOptions $options = null
    ): PromiseInterface;

    /**
     * Creates a range of pages to a page blob.
     *
     * @param string                            $container name of the container
     * @param string                            $blob      name of the blob
     * @param Range                             $range     Can be up to 4 MB in size
     * @param string                            $content   the blob contents
     * @param BlobModels\CreateBlobPagesOptions $options   optional parameters
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/ee691975.aspx
     */
    public function createBlobPages(
        string $container,
        string $blob,
        Range $range,
        string $content,
        ?BlobModels\CreateBlobPagesOptions $options = null
    ): BlobModels\CreateBlobPagesResult;

    /**
     * Creates promise to create a range of pages to a page blob.
     *
     * @param string                            $container name of the container
     * @param string                            $blob      name of the blob
     * @param Range                             $range     Can be up to 4 MB in
     *                                                     size. Note that ranges
     *                                                     must be aligned to 512
     *                                                     (0-511, 512-1023)
     * @param string|resource|StreamInterface   $content   the blob contents.
     * @param BlobModels\CreateBlobPagesOptions $options   optional parameters
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/ee691975.aspx
     */
    public function createBlobPagesAsync(
        string $container,
        string $blob,
        Range $range,
        $content,
        ?BlobModels\CreateBlobPagesOptions $options = null
    ): PromiseInterface;

    /**
     * Creates a new block to be committed as part of a block blob.
     *
     * @param string                            $container name of the container
     * @param string                            $blob      name of the blob
     * @param string                            $blockId   must be less than or equal to
     * 64 bytes in size. For a given blob, the length of the value specified for the
     * blockid parameter must be the same size for each block.
     * @param string                            $content   the blob block contents
     * @param BlobModels\CreateBlobBlockOptions $options   optional parameters
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/dd135726.aspx
     */
    public function createBlobBlock(
        string $container,
        string $blob,
        string $blockId,
        string $content,
        ?BlobModels\CreateBlobBlockOptions $options = null
    ): BlobModels\PutBlockResult;

    /**
     * Creates a new block to be committed as part of a block blob.
     *
     * @param string                              $container name of the container
     * @param string                              $blob      name of the blob
     * @param string                              $blockId   must be less than or
     *                                                       equal to 64 bytes in
     *                                                       size. For a given
     *                                                       blob, the length of
     *                                                       the value specified
     *                                                       for the blockid
     *                                                       parameter must
     *                                                       be the same size for
     *                                                       each block.
     * @param resource|string|StreamInterface     $content   the blob block contents
     * @param BlobModels\CreateBlobBlockOptions   $options   optional parameters
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/dd135726.aspx
     */
    public function createBlobBlockAsync(
        string $container,
        string $blob,
        string $blockId,
        $content,
        ?BlobModels\CreateBlobBlockOptions $options = null
    ): PromiseInterface;

    /**
     * Commits a new block of data to the end of an existing append blob.
     *
     * @param string                          $container name of the container
     * @param string                          $blob      name of the blob
     * @param resource|string|StreamInterface $content   the blob block contents
     * @param BlobModels\AppendBlockOptions   $options   optional parameters
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/fileservices/append-block
     */
    public function appendBlock(
        string $container,
        string $blob,
        $content,
        ?BlobModels\AppendBlockOptions $options = null
    ): BlobModels\AppendBlockResult;

    /**
     * Creates promise to commit a new block of data to the end of an existing append blob.
     *
     * @param string                          $container name of the container
     * @param string                          $blob      name of the blob
     * @param resource|string|StreamInterface $content   the blob block contents
     * @param BlobModels\AppendBlockOptions   $options   optional parameters
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/fileservices/append-block
     */
    public function appendBlockAsync(
        string $container,
        string $blob,
        $content,
        ?BlobModels\AppendBlockOptions $options = null
    ): PromiseInterface;

    /**
     * This method writes a blob by specifying the list of block IDs that make up the
     * blob. In order to be written as part of a blob, a block must have been
     * successfully written to the server in a prior createBlobBlock method.
     *
     * You can call Put Block List to update a blob by uploading only those blocks
     * that have changed, then committing the new and existing blocks together.
     * You can do this by specifying whether to commit a block from the committed
     * block list or from the uncommitted block list, or to commit the most recently
     * uploaded version of the block, whichever list it may belong to.
     *
     * @param string                                  $container name of the container
     * @param string                                  $blob      name of the blob
     * @param BlobModels\BlockList|BlobModels\Block[] $blockList the block list entries
     * @param BlobModels\CommitBlobBlocksOptions      $options   optional parameters
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/dd179467.aspx
     */
    public function commitBlobBlocks(
        string $container,
        string $blob,
        BlobModels\BlockList|array $blockList,
        ?BlobModels\CommitBlobBlocksOptions $options = null
    ): BlobModels\PutBlobResult;

    /**
     * This method writes a blob by specifying the list of block IDs that make up the
     * blob. In order to be written as part of a blob, a block must have been
     * successfully written to the server in a prior createBlobBlock method.
     *
     * You can call Put Block List to update a blob by uploading only those blocks
     * that have changed, then committing the new and existing blocks together.
     * You can do this by specifying whether to commit a block from the committed
     * block list or from the uncommitted block list, or to commit the most recently
     * uploaded version of the block, whichever list it may belong to.
     *
     * @param string                                  $container name of the container
     * @param string                                  $blob      name of the blob
     * @param BlobModels\BlockList|BlobModels\Block[] $blockList the block list
     *                                                           entries
     * @param BlobModels\CommitBlobBlocksOptions      $options   optional parameters
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/dd179467.aspx
     */
    public function commitBlobBlocksAsync(
        string $container,
        string $blob,
        BlobModels\BlockList|array $blockList,
        ?BlobModels\CommitBlobBlocksOptions $options = null
    ): PromiseInterface;

    /**
     * Retrieves the list of blocks that have been uploaded as part of a block blob.
     *
     * There are two block lists maintained for a blob:
     * 1) Committed Block List: The list of blocks that have been successfully
     *    committed to a given blob with commitBlobBlocks.
     * 2) Uncommitted Block List: The list of blocks that have been uploaded for a
     *    blob using Put Block (REST API), but that have not yet been committed.
     *    These blocks are stored in Windows Azure in association with a blob, but do
     *    not yet form part of the blob.
     *
     * @param string                           $container name of the container
     * @param string                           $blob      name of the blob
     * @param BlobModels\ListBlobBlocksOptions $options   optional parameters
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/dd179400.aspx
     */
    public function listBlobBlocks(
        string $container,
        string $blob,
        ?BlobModels\ListBlobBlocksOptions $options = null
    ): BlobModels\ListBlobBlocksResult;

    /**
     * Creates promise to retrieve the list of blocks that have been uploaded as
     * part of a block blob.
     *
     * There are two block lists maintained for a blob:
     * 1) Committed Block List: The list of blocks that have been successfully
     *    committed to a given blob with commitBlobBlocks.
     * 2) Uncommitted Block List: The list of blocks that have been uploaded for a
     *    blob using Put Block (REST API), but that have not yet been committed.
     *    These blocks are stored in Windows Azure in association with a blob, but do
     *    not yet form part of the blob.
     *
     * @param string                           $container name of the container
     * @param string                           $blob      name of the blob
     * @param BlobModels\ListBlobBlocksOptions $options   optional parameters
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/dd179400.aspx
     */
    public function listBlobBlocksAsync(
        string $container,
        string $blob,
        ?BlobModels\ListBlobBlocksOptions $options = null
    ): PromiseInterface;

    /**
     * Returns all properties and metadata on the blob.
     *
     * @param string                              $container name of the container
     * @param string                              $blob      name of the blob
     * @param BlobModels\GetBlobPropertiesOptions $options   optional parameters
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/dd179394.aspx
     */
    public function getBlobProperties(
        string $container,
        string $blob,
        ?BlobModels\GetBlobPropertiesOptions $options = null
    ): BlobModels\GetBlobPropertiesResult;

    /**
     * Creates promise to return all properties and metadata on the blob.
     *
     * @param string                              $container name of the container
     * @param string                              $blob      name of the blob
     * @param BlobModels\GetBlobPropertiesOptions $options   optional parameters
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/dd179394.aspx
     */
    public function getBlobPropertiesAsync(
        string $container,
        string $blob,
        ?BlobModels\GetBlobPropertiesOptions $options = null
    ): PromiseInterface;

    /**
     * Returns all properties and metadata on the blob.
     *
     * @param string                            $container name of the container
     * @param string                            $blob      name of the blob
     * @param BlobModels\GetBlobMetadataOptions $options   optional parameters
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/dd179350.aspx
     */
    public function getBlobMetadata(
        string $container,
        string $blob,
        ?BlobModels\GetBlobMetadataOptions $options = null
    ): BlobModels\GetBlobMetadataResult;

    /**
     * Creates promise to return all properties and metadata on the blob.
     *
     * @param string                            $container name of the container
     * @param string                            $blob      name of the blob
     * @param BlobModels\GetBlobMetadataOptions $options   optional parameters
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/dd179350.aspx
     */
    public function getBlobMetadataAsync(
        string $container,
        string $blob,
        ?BlobModels\GetBlobMetadataOptions $options = null
    ): PromiseInterface;

    /**
     * Returns a list of active page ranges for a page blob. Active page ranges are
     * those that have been populated with data.
     *
     * @param string                               $container name of the container
     * @param string                               $blob      name of the blob
     * @param BlobModels\ListPageBlobRangesOptions $options   optional parameters
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/ee691973.aspx
     */
    public function listPageBlobRanges(
        string $container,
        string $blob,
        ?BlobModels\ListPageBlobRangesOptions $options = null
    ): BlobModels\ListPageBlobRangesResult;

    /**
     * Creates promise to return a list of active page ranges for a page blob.
     * Active page ranges are those that have been populated with data.
     *
     * @param string                               $container name of the
     *                                                        container
     * @param string                               $blob      name of the blob
     * @param BlobModels\ListPageBlobRangesOptions $options   optional parameters
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/ee691973.aspx
     */
    public function listPageBlobRangesAsync(
        string $container,
        string $blob,
        ?BlobModels\ListPageBlobRangesOptions $options = null
    ): PromiseInterface;

    /**
     * Returns a list of page ranges that have been updated or cleared.
     *
     * Returns a list of page ranges that have been updated or cleared since
     * the snapshot specified by `previousSnapshotTime`. Gets all of the page
     * ranges by default, or only the page ranges over a specific range of
     * bytes if `rangeStart` and `rangeEnd` in the `options` are specified.
     *
     * @param string                               $container             name of the container
     * @param string                               $blob                  name of the blob
     * @param string                               $previousSnapshotTime  previous snapshot time
     *                                                                    for comparison which
     *                                                                    should be prior to the
     *                                                                    snapshot time defined
     *                                                                    in `options`
     * @param BlobModels\ListPageBlobRangesOptions $options               optional parameters
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/version-2015-07-08
     */
    public function listPageBlobRangesDiff(
        string $container,
        string $blob,
        string $previousSnapshotTime,
        ?BlobModels\ListPageBlobRangesOptions $options = null
    ): BlobModels\ListPageBlobRangesDiffResult;

    /**
     * Creates promise to return a list of page ranges that have been updated
     * or cleared.
     *
     * Creates promise to return a list of page ranges that have been updated
     * or cleared since the snapshot specified by `previousSnapshotTime`. Gets
     * all of the page ranges by default, or only the page ranges over a specific
     * range of bytes if `rangeStart` and `rangeEnd` in the `options` are specified.
     *
     * @param string                               $container               name of the container
     * @param string                               $blob                    name of the blob
     * @param string                               $previousSnapshotTime    previous snapshot time
     *                                                                      for comparison which
     *                                                                      should be prior to the
     *                                                                      snapshot time defined
     *                                                                      in `options`
     * @param BlobModels\ListPageBlobRangesOptions $options                 optional parameters
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/ee691973.aspx
     */
    public function listPageBlobRangesDiffAsync(
        string $container,
        string $blob,
        string $previousSnapshotTime,
        ?BlobModels\ListPageBlobRangesOptions $options = null
    ): PromiseInterface;

    /**
     * Sets blob tier on the blob.
     *
     * @param string                        $container name
     * @param string                        $blob      name of the blob
     * @param BlobModels\SetBlobTierOptions $options   optional parameters
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/set-blob-tier
     */
    public function setBlobTier(
        string $container,
        string $blob,
        ?BlobModels\SetBlobTierOptions $options = null
    ): void;

    /**
     * Sets blob tier on the blob.
     *
     * @param string                        $container name
     * @param string                        $blob      name of the blob
     * @param BlobModels\SetBlobTierOptions $options   optional parameters
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/set-blob-tier
     */
    public function setBlobTierAsync(
        string $container,
        string $blob,
        ?BlobModels\SetBlobTierOptions $options = null
    ): PromiseInterface;

    /**
     * Sets system properties defined for a blob.
     *
     * @param string                              $container name of the container
     * @param string                              $blob      name of the blob
     * @param BlobModels\SetBlobPropertiesOptions $options   optional parameters
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/ee691966.aspx
     */
    public function setBlobProperties(
        string $container,
        string $blob,
        ?BlobModels\SetBlobPropertiesOptions $options = null
    ): BlobModels\SetBlobPropertiesResult;

    /**
     * Creates promise to set system properties defined for a blob.
     *
     * @param string                              $container name of the container
     * @param string                              $blob      name of the blob
     * @param BlobModels\SetBlobPropertiesOptions $options   optional parameters
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/ee691966.aspx
     */
    public function setBlobPropertiesAsync(
        string $container,
        string $blob,
        ?BlobModels\SetBlobPropertiesOptions $options = null
    ): PromiseInterface;

    /**
     * Sets metadata headers on the blob.
     *
     * @param string                         $container name of the container
     * @param string                         $blob      name of the blob
     * @param array                          $metadata  key/value pair representation
     * @param BlobModels\BlobServiceOptions  $options   optional parameters
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/dd179414.aspx
     */
    public function setBlobMetadata(
        string $container,
        string $blob,
        array $metadata,
        ?BlobModels\BlobServiceOptions $options = null
    ): BlobModels\SetBlobMetadataResult;

    /**
     * Creates promise to set metadata headers on the blob.
     *
     * @param string                            $container name of the container
     * @param string                            $blob      name of the blob
     * @param array                             $metadata  key/value pair representation
     * @param BlobModels\BlobServiceOptions     $options   optional parameters
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/dd179414.aspx
     */
    public function setBlobMetadataAsync(
        string $container,
        string $blob,
        array $metadata,
        ?BlobModels\BlobServiceOptions $options = null
    ): PromiseInterface;

    /**
     * Downloads a blob to a file, the result contains its metadata and
     * properties. The result will not contain a stream pointing to the
     * content of the file.
     *
     * @param string                    $path      The path and name of the file
     * @param string                    $container name of the container
     * @param string                    $blob      name of the blob
     * @param BlobModels\GetBlobOptions $options   optional parameters
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/dd179440.aspx
     */
    public function saveBlobToFile(
        string $path,
        string $container,
        string $blob,
        ?BlobModels\GetBlobOptions $options = null
    ): BlobModels\GetBlobResult;

    /**
     * Creates promise to download a blob to a file, the result contains its
     * metadata and properties. The result will not contain a stream pointing
     * to the content of the file.
     *
     * @param string                    $path      The path and name of the file
     * @param string                    $container name of the container
     * @param string                    $blob      name of the blob
     * @param BlobModels\GetBlobOptions $options   optional parameters
     *
     * @throws \Exception
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/dd179440.aspx
     */
    public function saveBlobToFileAsync(
        string $path,
        string $container,
        string $blob,
        ?BlobModels\GetBlobOptions $options = null
    ): PromiseInterface;

    /**
     * Undeletes a blob.
     *
     * @param string                          $container name of the container
     * @param string                          $blob      name of the blob
     * @param BlobModels\UndeleteBlobOptions  $options   optional parameters
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/undelete-blob
     */
    public function undeleteBlob(
        string $container,
        string $blob,
        ?BlobModels\UndeleteBlobOptions $options = null
    ): void;

    /**
     * Undeletes a blob.
     *
     * @param string                          $container name of the container
     * @param string                          $blob      name of the blob
     * @param BlobModels\UndeleteBlobOptions  $options   optional parameters
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/undelete-blob
     */
    public function undeleteBlobAsync(
        string $container,
        string $blob,
        ?BlobModels\UndeleteBlobOptions $options = null
    ): PromiseInterface;

    /**
     * Reads or downloads a blob from the system, including its metadata and
     * properties.
     *
     * @param string                    $container name of the container
     * @param string                    $blob      name of the blob
     * @param BlobModels\GetBlobOptions $options   optional parameters
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/dd179440.aspx
     */
    public function getBlob(
        string $container,
        string $blob,
        ?BlobModels\GetBlobOptions $options = null
    ): BlobModels\GetBlobResult;

    /**
     * Creates promise to read or download a blob from the system, including its
     * metadata and properties.
     *
     * @param string                    $container name of the container
     * @param string                    $blob      name of the blob
     * @param BlobModels\GetBlobOptions $options   optional parameters
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/dd179440.aspx
     */
    public function getBlobAsync(
        string $container,
        string $blob,
        ?BlobModels\GetBlobOptions $options = null
    ): PromiseInterface;

    /**
     * Deletes a blob or blob snapshot.
     *
     * Note that if the snapshot entry is specified in the $options then only this
     * blob snapshot is deleted. To delete all blob snapshots, do not set Snapshot
     * and just set getDeleteSnaphotsOnly to true.
     *
     * @param string                       $container name of the container
     * @param string                       $blob      name of the blob
     * @param BlobModels\DeleteBlobOptions $options   optional parameters
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/dd179413.aspx
     */
    public function deleteBlob(
        string $container,
        string $blob,
        ?BlobModels\DeleteBlobOptions $options = null
    ): void;

    /**
     * Creates promise to delete a blob or blob snapshot.
     *
     * Note that if the snapshot entry is specified in the $options then only this
     * blob snapshot is deleted. To delete all blob snapshots, do not set Snapshot
     * and just set getDeleteSnaphotsOnly to true.
     *
     * @param string                       $container name of the container
     * @param string                       $blob      name of the blob
     * @param BlobModels\DeleteBlobOptions $options   optional parameters
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/dd179413.aspx
     */
    public function deleteBlobAsync(
        string $container,
        string $blob,
        ?BlobModels\DeleteBlobOptions $options = null
    ): PromiseInterface;

    /**
     * Creates a snapshot of a blob.
     *
     * @param string                               $container name of the container
     * @param string                               $blob      name of the blob
     * @param BlobModels\CreateBlobSnapshotOptions $options   optional parameters
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/ee691971.aspx
     */
    public function createBlobSnapshot(
        string $container,
        string $blob,
        ?BlobModels\CreateBlobSnapshotOptions $options = null
    ): BlobModels\CreateBlobSnapshotResult;

    /**
     * Creates promise to create a snapshot of a blob.
     *
     * @param string                               $container The name of the
     *                                                        container.
     * @param string                               $blob      The name of the
     *                                                        blob.
     * @param BlobModels\CreateBlobSnapshotOptions $options   The optional
     *                                                        parameters.
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/ee691971.aspx
     */
    public function createBlobSnapshotAsync(
        string $container,
        string $blob,
        ?BlobModels\CreateBlobSnapshotOptions $options = null
    ): PromiseInterface;

    /**
     * Copies a source blob to a destination blob within the same storage account.
     *
     * @param string                     $destinationContainer name of container
     * @param string                     $destinationBlob      name of blob
     * @param string                     $sourceContainer      name of container
     * @param string                     $sourceBlob           name of blob
     * @param BlobModels\CopyBlobOptions $options              optional parameters
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/dd894037.aspx
     */
    public function copyBlob(
        string $destinationContainer,
        string $destinationBlob,
        string $sourceContainer,
        string $sourceBlob,
        ?BlobModels\CopyBlobOptions $options = null
    ): BlobModels\CopyBlobResult;

    /**
     * Creates promise to copy a source blob to a destination blob within the
     * same storage account.
     *
     * @param string                     $destinationContainer name of the
     *                                                         destination
     *                                                         container
     * @param string                     $destinationBlob      name of the
     *                                                         destination blob
     * @param string                     $sourceContainer      name of the source
     *                                                         container
     * @param string                     $sourceBlob           name of the source
     *                                                         blob
     * @param BlobModels\CopyBlobOptions $options              optional parameters
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/dd894037.aspx
     */
    public function copyBlobAsync(
        string $destinationContainer,
        string $destinationBlob,
        string $sourceContainer,
        string $sourceBlob,
        ?BlobModels\CopyBlobOptions $options = null
    ): PromiseInterface;

    /**
     * Copies from a source URL to a destination blob.
     *
     * @param string                            $destinationContainer name of the
     *                                                                destination
     *                                                                container
     * @param string                            $destinationBlob      name of the
     *                                                                destination
     *                                                                blob
     * @param string                            $sourceURL            URL of the
     *                                                                source
     *                                                                resource
     * @param BlobModels\CopyBlobFromURLOptions $options              optional
     *                                                                parameters
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/dd894037.aspx
     */
    public function copyBlobFromURL(
        string $destinationContainer,
        string $destinationBlob,
        string $sourceURL,
        ?BlobModels\CopyBlobFromURLOptions $options = null
    ): BlobModels\CopyBlobResult;

    /**
     * Creates promise to copy from source URL to a destination blob.
     *
     * @param string                            $destinationContainer name of the
     *                                                                destination
     *                                                                container
     * @param string                            $destinationBlob      name of the
     *                                                                destination
     *                                                                blob
     * @param string                            $sourceURL            URL of the
     *                                                                source
     *                                                                resource
     * @param BlobModels\CopyBlobFromURLOptions $options              optional
     *                                                                parameters
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/dd894037.aspx
     */
    public function copyBlobFromURLAsync(
        string $destinationContainer,
        string $destinationBlob,
        string $sourceURL,
        ?BlobModels\CopyBlobFromURLOptions $options = null
    ): PromiseInterface;

    /**
     * Abort a blob copy operation
     *
     * @param string                        $container            name of the container
     * @param string                        $blob                 name of the blob
     * @param string                        $copyId               copy operation identifier.
     * @param BlobModels\BlobServiceOptions $options              optional parameters
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/fileservices/abort-copy-blob
     */
    public function abortCopy(
        string $container,
        string $blob,
        string $copyId,
        ?BlobModels\BlobServiceOptions $options = null
    ): void;

    /**
     * Creates promise to abort a blob copy operation
     *
     * @param string                        $container            name of the container
     * @param string                        $blob                 name of the blob
     * @param string                        $copyId               copy operation identifier.
     * @param BlobModels\BlobServiceOptions $options              optional parameters
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/fileservices/abort-copy-blob
     */
    public function abortCopyAsync(
        string $container,
        string $blob,
        string $copyId,
        ?BlobModels\BlobServiceOptions $options = null
    ): PromiseInterface;

    /**
     * Establishes an exclusive write lock on a blob. To write to a locked
     * blob, a client must provide a lease ID.
     *
     * @param string                     $container         name of the container
     * @param string                     $blob              name of the blob
     * @param string                     $proposedLeaseId   lease id when acquiring
     * @param int                        $leaseDuration     the lease duration. A non-infinite
     *                                                      lease can be between 15 and 60 seconds.
     *                                                      Default is never to expire.
     * @param BlobModels\BlobServiceOptions  $options       optional parameters
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/ee691972.aspx
     */
    public function acquireLease(
        string $container,
        string $blob,
        ?string $proposedLeaseId = null,
        ?int $leaseDuration = null,
        ?BlobModels\BlobServiceOptions $options = null
    ): BlobModels\LeaseResult;

    /**
     * Creates promise to establish an exclusive one-minute write lock on a blob.
     * To write to a locked blob, a client must provide a lease ID.
     *
     * @param string                     $container         name of the container
     * @param string                     $blob              name of the blob
     * @param string                     $proposedLeaseId   lease id when acquiring
     * @param int                        $leaseDuration     the lease duration. A non-infinite
     *                                                      lease can be between 15 and 60 seconds.
     *                                                      Default is never to expire.
     * @param BlobModels\BlobServiceOptions  $options       optional parameters
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/ee691972.aspx
     */
    public function acquireLeaseAsync(
        string $container,
        string $blob,
        ?string $proposedLeaseId = null,
        ?int $leaseDuration = null,
        ?BlobModels\BlobServiceOptions $options = null
    ): PromiseInterface;

    /**
     * change an existing lease
     *
     * @param string                        $container         name of the container
     * @param string                        $blob              name of the blob
     * @param string                        $leaseId           lease id when acquiring
     * @param string                        $proposedLeaseId   lease id when acquiring
     * @param BlobModels\BlobServiceOptions $options           optional parameters
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/fileservices/lease-blob
     */
    public function changeLease(
        string $container,
        string $blob,
        string $leaseId,
        string $proposedLeaseId,
        ?BlobModels\BlobServiceOptions $options = null
    ): BlobModels\LeaseResult;

    /**
     * Creates promise to change an existing lease
     *
     * @param string                        $container         name of the container
     * @param string                        $blob              name of the blob
     * @param string                        $leaseId           lease id when acquiring
     * @param string                        $proposedLeaseId   the proposed lease id
     * @param BlobModels\BlobServiceOptions $options           optional parameters
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/fileservices/lease-blob
     */
    public function changeLeaseAsync(
        string $container,
        string $blob,
        string $leaseId,
        string $proposedLeaseId,
        ?BlobModels\BlobServiceOptions $options = null
    ): PromiseInterface;

    /**
     * Renews an existing lease
     *
     * @param string                        $container name of the container
     * @param string                        $blob      name of the blob
     * @param string                        $leaseId   lease id when acquiring
     * @param BlobModels\BlobServiceOptions $options   optional parameters
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/ee691972.aspx
     */
    public function renewLease(
        string $container,
        string $blob,
        string $leaseId,
        ?BlobModels\BlobServiceOptions $options = null
    ): BlobModels\LeaseResult;

    /**
     * Creates promise to renew an existing lease
     *
     * @param string                        $container name of the container
     * @param string                        $blob      name of the blob
     * @param string                        $leaseId   lease id when acquiring
     * @param BlobModels\BlobServiceOptions $options   optional parameters
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/ee691972.aspx
     */
    public function renewLeaseAsync(
        string $container,
        string $blob,
        string $leaseId,
        ?BlobModels\BlobServiceOptions $options = null
    ): PromiseInterface;

    /**
     * Frees the lease if it is no longer needed so that another client may
     * immediately acquire a lease against the blob.
     *
     * @param string                        $container name of the container
     * @param string                        $blob      name of the blob
     * @param string                        $leaseId   lease id when acquiring
     * @param BlobModels\BlobServiceOptions $options   optional parameters
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/ee691972.aspx
     */
    public function releaseLease(
        string $container,
        string $blob,
        string $leaseId,
        ?BlobModels\BlobServiceOptions $options = null
    ): void;

    /**
     * Creates promise to free the lease if it is no longer needed so that
     * another client may immediately acquire a lease against the blob.
     *
     * @param string                        $container name of the container
     * @param string                        $blob      name of the blob
     * @param string                        $leaseId   lease id when acquiring
     * @param BlobModels\BlobServiceOptions $options   optional parameters
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/ee691972.aspx
     */
    public function releaseLeaseAsync(
        string $container,
        string $blob,
        string $leaseId,
        ?BlobModels\BlobServiceOptions $options = null
    ): PromiseInterface;

    /**
     * Ends the lease but ensure that another client cannot acquire a new lease until
     * the current lease period has expired.
     *
     * @param string                        $container name of the container
     * @param string                        $blob      name of the blob
     * @param BlobModels\BlobServiceOptions $options   optional parameters
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/ee691972.aspx
     */
    public function breakLease(
        string $container,
        string $blob,
        ?int $breakPeriod = null,
        ?BlobModels\BlobServiceOptions $options = null
    ): BreakLeaseResult;

    /**
     * Creates promise to end the lease but ensure that another client cannot
     * acquire a new lease until the current lease period has expired.
     *
     * @param string                        $container name of the container
     * @param string                        $blob      name of the blob
     * @param BlobModels\BlobServiceOptions $options   optional parameters
     *
     * @see http://msdn.microsoft.com/en-us/library/windowsazure/ee691972.aspx
     */
    public function breakLeaseAsync(
        string $container,
        string $blob,
        ?int $breakPeriod = null,
        ?BlobModels\BlobServiceOptions $options = null
    ): PromiseInterface;

}
