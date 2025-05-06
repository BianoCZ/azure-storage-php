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
 * @package   MicrosoftAzure\Storage\File\Internal
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2017 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */

namespace MicrosoftAzure\Storage\File\Internal;

use GuzzleHttp\Promise\PromiseInterface;
use MicrosoftAzure\Storage\Common\Models\GetServicePropertiesResult;
use MicrosoftAzure\Storage\Common\Models\Range;
use MicrosoftAzure\Storage\Common\Models\ServiceOptions;
use MicrosoftAzure\Storage\Common\Models\ServiceProperties;
use MicrosoftAzure\Storage\File\Models as FileModels;

/**
 * This interface has all REST APIs provided by Windows Azure for File service.
 *
 * @ignore
 * @category  Microsoft
 * @package   MicrosoftAzure\Storage\File\Internal
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2017 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 * @see       https://docs.microsoft.com/en-us/rest/api/storageservices/file-service-rest-api
 */
interface IFile
{
    /**
     * Gets the properties of the service.
     *
     * @param ServiceOptions $options optional service options.
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/get-file-service-properties
     */
    public function getServiceProperties(?ServiceOptions $options = null): GetServicePropertiesResult;

    /**
     * Creates promise to get the properties of the service.
     *
     * @param ServiceOptions $options The optional parameters.
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/get-file-service-properties
     */
    public function getServicePropertiesAsync(?ServiceOptions $options = null): PromiseInterface;

    /**
     * Sets the properties of the service.
     *
     * @param ServiceProperties $serviceProperties new service properties
     * @param ServiceOptions    $options           optional parameters
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/set-file-service-properties
     */
    public function setServiceProperties(
        ServiceProperties $serviceProperties,
        ?ServiceOptions $options = null
    ): void;

    /**
     * Creates the promise to set the properties of the service.
     *
     * It's recommended to use getServiceProperties, alter the returned object and
     * then use setServiceProperties with this altered object.
     *
     * @param ServiceProperties $serviceProperties new service properties.
     * @param ServiceOptions    $options           optional parameters
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/set-file-service-properties
     */
    public function setServicePropertiesAsync(
        ServiceProperties $serviceProperties,
        ?ServiceOptions $options = null
    ): PromiseInterface;

    /**
     * Returns a list of the shares under the specified account
     *
     * @param  FileModels\ListSharesOptions|null $options The optional parameters
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/list-shares
     */
    public function listShares(?FileModels\ListSharesOptions $options = null): FileModels\ListSharesResult;

    /**
     * Create a promise to return a list of the shares under the specified account
     *
     * @param  FileModels\ListSharesOptions|null $options The optional parameters
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/list-shares
     */
    public function listSharesAsync(?FileModels\ListSharesOptions $options = null): PromiseInterface;

    /**
     * Creates a new share in the given storage account.
     *
     * @param string                             $share   The share name.
     * @param FileModels\CreateShareOptions|null $options The optional parameters.
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/create-share
     */
    public function createShare(
        string $share,
        ?FileModels\CreateShareOptions $options = null
    ): void;

    /**
     * Creates promise to create a new share in the given storage account.
     *
     * @param string                             $share   The share name.
     * @param FileModels\CreateShareOptions|null $options The optional parameters.
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/create-share
     */
    public function createShareAsync(
        string $share,
        ?FileModels\CreateShareOptions $options = null
    ): PromiseInterface;

    /**
     * Deletes a share in the given storage account.
     *
     * @param string                             $share   The share name.
     * @param FileModels\FileServiceOptions|null $options optional parameters
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/delete-share
     */
    public function deleteShare(
        string $share,
        ?FileModels\FileServiceOptions $options = null
    ): void;

    /**
     * Create a promise for deleting a share.
     *
     * @param  string                             $share   name of the share
     * @param  FileModels\FileServiceOptions|null $options optional parameters
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/delete-share
     */
    public function deleteShareAsync(
        string $share,
        ?FileModels\FileServiceOptions $options = null
    ): PromiseInterface;

    /**
     * Returns all properties and metadata on the share.
     *
     * @param string                             $share   name
     * @param FileModels\FileServiceOptions|null $options optional parameters
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/get-share-properties
     */
    public function getShareProperties(
        string $share,
        ?FileModels\FileServiceOptions $options = null
    ): FileModels\GetSharePropertiesResult;

    /**
     * Create promise to return all properties and metadata on the share.
     *
     * @param string                             $share   name
     * @param FileModels\FileServiceOptions|null $options optional parameters
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/get-share-properties
     */
    public function getSharePropertiesAsync(
        string $share,
        ?FileModels\FileServiceOptions $options = null
    ): PromiseInterface;

    /**
     * Sets quota of the share.
     *
     * @param string                             $share   name
     * @param int                                $quota   quota of the share
     * @param FileModels\FileServiceOptions|null $options optional parameters
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/set-share-properties
     */
    public function setShareProperties(
        string $share,
        int $quota,
        ?FileModels\FileServiceOptions $options = null
    ): void;

    /**
     * Creates promise to set quota the share.
     *
     * @param string                             $share   name
     * @param int                                $quota   quota of the share
     * @param FileModels\FileServiceOptions|null $options optional parameters
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/set-share-properties
     */
    public function setSharePropertiesAsync(
        string $share,
        int $quota,
        ?FileModels\FileServiceOptions $options = null
    ): PromiseInterface;

    /**
     * Returns only user-defined metadata for the specified share.
     *
     * @param string                             $share   name
     * @param FileModels\FileServiceOptions|null $options optional parameters
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/get-share-metadata
     */
    public function getShareMetadata(
        string $share,
        ?FileModels\FileServiceOptions $options = null
    ): FileModels\GetSharePropertiesResult;

    /**
     * Create promise to return only user-defined metadata for the specified
     * share.
     *
     * @param string                             $share   name
     * @param FileModels\FileServiceOptions|null $options optional parameters
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/get-share-metadata
     */
    public function getShareMetadataAsync(
        string $share,
        ?FileModels\FileServiceOptions $options = null
    ): PromiseInterface;

    /**
     * Updates metadata of the share.
     *
     * @param string                             $share    name
     * @param array                              $metadata metadata key/value pair.
     * @param FileModels\FileServiceOptions|null $options optional  parameters
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/set-share-metadata
     */
    public function setShareMetadata(
        string $share,
        array $metadata,
        ?FileModels\FileServiceOptions $options = null
    ): void;

    /**
     * Creates promise to update metadata headers on the share.
     *
     * @param string                             $share    name
     * @param array                              $metadata metadata key/value pair.
     * @param FileModels\FileServiceOptions|null $options optional  parameters
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/set-share-metadata
     */
    public function setShareMetadataAsync(
        string $share,
        array $metadata,
        ?FileModels\FileServiceOptions $options = null
    ): PromiseInterface;

    /**
     * Gets the access control list (ACL) for the share.
     *
     * @param string                             $share   The share name.
     * @param FileModels\FileServiceOptions|null $options The optional parameters.
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/get-share-acl
     */
    public function getShareAcl(
        string $share,
        ?FileModels\FileServiceOptions $options = null
    ): FileModels\GetShareACLResult;

    /**
     * Creates the promise to get the access control list (ACL) for the share.
     *
     * @param string                             $share   The share name.
     * @param FileModels\FileServiceOptions|null $options The optional parameters.
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/get-share-acl
     */
    public function getShareAclAsync(
        string $share,
        ?FileModels\FileServiceOptions $options = null
    ): PromiseInterface;

    /**
     * Sets the ACL and any share-level access policies for the share.
     *
     * @param string                             $share   name
     * @param FileModels\ShareACL                $acl     access control list
     *                                                    for share
     * @param FileModels\FileServiceOptions|null $options optional parameters
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/set-share-acl
     */
    public function setShareAcl(
        string $share,
        FileModels\ShareACL $acl,
        ?FileModels\FileServiceOptions $options = null
    ): void;

    /**
     * Creates promise to set the ACL and any share-level access policies
     * for the share.
     *
     * @param string                             $share   name
     * @param FileModels\ShareACL                $acl     access control list
     * for share
     * @param FileModels\FileServiceOptions|null $options optional parameters
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/set-share-acl
     */
    public function setShareAclAsync(
        string $share,
        FileModels\ShareACL $acl,
        ?FileModels\FileServiceOptions $options = null
    ): PromiseInterface;

    /**
     * Get the statistics related to the share.
     *
     * @param  string                             $share   The name of the share.
     * @param  FileModels\FileServiceOptions|null $options The request options.
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/get-share-stats
     */
    public function getShareStats(
        string $share,
        ?FileModels\FileServiceOptions $options = null
    ): FileModels\GetShareStatsResult;

    /**
     * Get the statistics related to the share.
     *
     * @param  string                             $share   The name of the share.
     * @param  FileModels\FileServiceOptions|null $options The request options.
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/get-share-stats
     */
    public function getShareStatsAsync(
        string $share,
        ?FileModels\FileServiceOptions $options = null
    ): PromiseInterface;

    /**
     * List directories and files under specified path.
     *
     * @param  string                              $share   The share that
     *                                                      contains all the
     *                                                      files and directories.
     * @param  string                              $path    The path to be listed.
     * @param  FileModels\ListDirectoriesAndFilesOptions|null $options Optional parameters.
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/list-directories-and-files
     */
    public function listDirectoriesAndFiles(
        string $share,
        string $path = '',
        ?FileModels\ListDirectoriesAndFilesOptions $options = null
    ): FileModels\ListDirectoriesAndFilesResult;

    /**
     * Creates promise to list directories and files under specified path.
     *
     * @param  string                              $share   The share that
     *                                                      contains all the
     *                                                      files and directories.
     * @param  string                              $path    The path to be listed.
     * @param  FileModels\ListDirectoriesAndFilesOptions|null $options Optional parameters.
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/list-directories-and-files
     */
    public function listDirectoriesAndFilesAsync(
        string $share,
        string $path = '',
        ?FileModels\ListDirectoriesAndFilesOptions $options = null
    ): PromiseInterface;

    /**
     * Creates a new directory in the given share and path.
     *
     * @param string                                 $share     The share name.
     * @param string                                 $path      The path to
     *                                                          create the directory.
     * @param FileModels\CreateDirectoryOptions|null $options   The optional parameters.
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/create-directory
     */
    public function createDirectory(
        string $share,
        string $path,
        ?FileModels\CreateDirectoryOptions $options = null
    ): void;

    /**
     * Creates a promise to create a new directory in the given share and path.
     *
     * @param string                                 $share     The share name.
     * @param string                                 $path      The path to
     *                                                          create the directory.
     * @param FileModels\CreateDirectoryOptions|null $options   The optional parameters.
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/create-directory
     */
    public function createDirectoryAsync(
        string $share,
        string $path,
        ?FileModels\CreateDirectoryOptions $options = null
    ): PromiseInterface;

    /**
     * Deletes a directory in the given share and path.
     *
     * @param string                             $share     The share name.
     * @param string                             $path      The path to delete
     *                                                      the directory.
     * @param FileModels\FileServiceOptions|null $options   The optional parameters.
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/delete-directory
     */
    public function deleteDirectory(
        string $share,
        string $path,
        ?FileModels\FileServiceOptions $options = null
    ): void;

    /**
     * Creates a promise to delete a new directory in the given share and path.
     *
     * @param string                             $share     The share name.
     * @param string                             $path      The path to delete
     *                                                      the directory.
     * @param FileModels\FileServiceOptions|null $options   The optional parameters.
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/delete-directory
     */
    public function deleteDirectoryAsync(
        string $share,
        string $path,
        ?FileModels\FileServiceOptions $options = null
    ): PromiseInterface;

    /**
     * Gets a directory's properties from the given share and path.
     *
     * @param string                            $share     The share name.
     * @param string                            $path      The path of the directory.
     * @param FileModelsFileServiceOptions|null $options   The optional parameters.
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/get-directory-properties
     */
    public function getDirectoryProperties(
        string $share,
        string $path,
        ?FileModels\FileServiceOptions $options = null
    ): FileModels\GetDirectoryPropertiesResult;

    /**
     * Creates promise to get a directory's properties from the given share
     * and path.
     *
     * @param string                            $share     The share name.
     * @param string                            $path      The path of the directory.
     * @param FileModelsFileServiceOptions|null $options   The optional parameters.
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/get-directory-properties
     */
    public function getDirectoryPropertiesAsync(
        string $share,
        string $path,
        ?FileModels\FileServiceOptions $options = null
    ): PromiseInterface;

    /**
     * Gets a directory's metadata from the given share and path.
     *
     * @param string                             $share     The share name.
     * @param string                             $path      The path of the directory.
     * @param FileModels\FileServiceOptions|null $options   The optional parameters.
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/get-directory-metadata
     */
    public function getDirectoryMetadata(
        string $share,
        string $path,
        ?FileModels\FileServiceOptions $options = null
    ): FileModels\GetDirectoryMetadataResult;

    /**
     * Creates promise to get a directory's metadata from the given share
     * and path.
     *
     * @param string                             $share     The share name.
     * @param string                             $path      The path of the directory.
     * @param FileModels\FileServiceOptions|null $options   The optional parameters.
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/get-directory-metadata
     */
    public function getDirectoryMetadataAsync(
        string $share,
        string $path,
        ?FileModels\FileServiceOptions $options = null
    ): PromiseInterface;

    /**
     * Sets a directory's metadata from the given share and path.
     *
     * @param string                             $share     The share name.
     * @param string                             $path      The path to delete
     *                                                      the directory.
     * @param array                              $metadata  The metadata to be set.
     * @param FileModels\FileServiceOptions|null $options   The optional parameters.
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/set-directory-metadata
     */
    public function setDirectoryMetadata(
        string $share,
        string $path,
        array $metadata,
        ?FileModels\FileServiceOptions $options = null
    ): void;

    /**
     * Creates promise to set a directory's metadata from the given share
     * and path.
     *
     * @param string                             $share     The share name.
     * @param string                             $path      The path to delete
     *                                                      the directory.
     * @param array                              $metadata  The metadata to be set.
     * @param FileModels\FileServiceOptions|null $options   The optional parameters.
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/set-directory-metadata
     */
    public function setDirectoryMetadataAsync(
        string $share,
        string $path,
        array $metadata,
        ?FileModels\FileServiceOptions $options = null
    ): PromiseInterface;

    /**
     * Create a new file.
     *
     * @param string                            $share   The share name.
     * @param string                            $path    The path and name of the file.
     * @param int                               $size    The size of the file.
     * @param FileModels\CreateFileOptions|null $options The optional parameters.
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/create-file
     */
    public function createFile(
        string $share,
        string $path,
        int $size,
        ?FileModels\CreateFileOptions $options = null
    ): void;

    /**
     * Creates promise to create a new file.
     *
     * @param string                            $share   The share name.
     * @param string                            $path    The path and name of the file.
     * @param int                               $size    The size of the file.
     * @param FileModels\CreateFileOptions|null $options The optional parameters.
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/create-file
     */
    public function createFileAsync(
        string $share,
        string $path,
        int $size,
        ?FileModels\CreateFileOptions $options = null
    ): PromiseInterface;

    /**
     * Deletes a file in the given share and path.
     *
     * @param string                             $share     The share name.
     * @param string                             $path      The path to delete the file.
     * @param FileModels\FileServiceOptions|null $options   The optional parameters.
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/delete-file2
     */
    public function deleteFile(
        string $share,
        string $path,
        ?FileModels\FileServiceOptions $options = null
    ): void;

    /**
     * Creates a promise to delete a new file in the given share and path.
     *
     * @param string                             $share     The share name.
     * @param string                             $path      The path to delete the file.
     * @param FileModels\FileServiceOptions|null $options   The optional parameters.
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/delete-file2
     */
    public function deleteFileAsync(
        string $share,
        string $path,
        ?FileModels\FileServiceOptions $options = null
    ): PromiseInterface;

    /**
     * Reads or downloads a file from the server, including its metadata and
     * properties.
     *
     * @param string                         $share   name of the share
     * @param string                         $path    path of the file to be get
     * @param FileModels\GetFileOptions|null $options optional parameters
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/get-file
     */
    public function getFile(
        string $share,
        string $path,
        ?FileModels\GetFileOptions $options = null
    ): FileModels\GetFileResult;

    /**
     * Creates promise to read or download a file from the server, including its
     * metadata and properties.
     *
     * @param string                         $share   name of the share
     * @param string                         $path    path of the file to be get
     * @param FileModels\GetFileOptions|null $options optional parameters
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/get-file
     */
    public function getFileAsync(
        string $share,
        string $path,
        ?FileModels\GetFileOptions $options = null
    ): PromiseInterface;

    /**
     * Gets a file's properties from the given share and path.
     *
     * @param string                             $share     The share name.
     * @param string                             $path      The path to delete the file.
     * @param FileModels\FileServiceOptions|null $options   The optional parameters.
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/get-file-properties
     */
    public function getFileProperties(
        string $share,
        string $path,
        ?FileModels\FileServiceOptions $options = null
    ): FileModels\FileProperties;

    /**
     * Creates promise to get a file's properties from the given share
     * and path.
     *
     * @param string                             $share     The share name.
     * @param string                             $path      The path to delete the file.
     * @param FileModels\FileServiceOptions|null $options   The optional parameters.
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/get-file-properties
     */
    public function getFilePropertiesAsync(
        string $share,
        string $path,
        ?FileModels\FileServiceOptions $options = null
    ): PromiseInterface;

    /**
     * Sets properties on the file.
     *
     * @param string                             $share      share name
     * @param string                             $path       path of the file
     * @param FileModels\FileProperties          $properties file properties.
     * @param FileModels\FileServiceOptions|null $options    optional     parameters
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/set-file-properties
     */
    public function setFileProperties(
        string $share,
        string $path,
        FileModels\FileProperties $properties,
        ?FileModels\FileServiceOptions $options = null
    ): void;

    /**
     * Creates promise to set properties on the file.
     *
     * @param string                             $share      share name
     * @param string                             $path       path of the file
     * @param FileModels\FileProperties          $properties file properties.
     * @param FileModels\FileServiceOptions|null $options    optional     parameters
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/set-file-properties
     */
    public function setFilePropertiesAsync(
        string $share,
        string $path,
        FileModels\FileProperties $properties,
        ?FileModels\FileServiceOptions $options = null
    ): PromiseInterface;

    /**
     * Gets a file's metadata from the given share and path.
     *
     * @param string                             $share     The share name.
     * @param string                             $path      The path of the file.
     * @param FileModels\FileServiceOptions|null $options   The optional parameters.
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/get-file-metadata
     */
    public function getFileMetadata(
        string $share,
        string $path,
        ?FileModels\FileServiceOptions $options = null
    ): FileModels\GetFileMetadataResult;

    /**
     * Creates promise to get a file's metadata from the given share
     * and path.
     *
     * @param string                             $share     The share name.
     * @param string                             $path      The path of the file.
     * @param FileModels\FileServiceOptions|null $options   The optional parameters.
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/get-file-metadata
     */
    public function getFileMetadataAsync(
        string $share,
        string $path,
        ?FileModels\FileServiceOptions $options = null
    ): PromiseInterface;

    /**
     * Sets a file's metadata from the given share and path.
     *
     * @param string                             $share     The share name.
     * @param string                             $path      The path to delete the file.
     * @param array                              $metadata  The metadata to be set.
     * @param FileModels\FileServiceOptions|null $options   The optional parameters.
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/set-file-metadata
     */
    public function setFileMetadata(
        string $share,
        string $path,
        array $metadata,
        ?FileModels\FileServiceOptions $options = null
    ): void;

    /**
     * Creates promise to set a file's metadata from the given share
     * and path.
     *
     * @param string                             $share     The share name.
     * @param string                             $path      The path to delete the file.
     * @param array                              $metadata  The metadata to be set.
     * @param FileModels\FileServiceOptions|null $options   The optional parameters.
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/set-file-metadata
     */
    public function setFileMetadataAsync(
        string $share,
        string $path,
        array $metadata,
        ?FileModels\FileServiceOptions $options = null
    ): PromiseInterface;

    /**
     * Writes range of bytes to a file. Range can be at most 4MB in length.
     *
     * @param  string                              $share   The share name.
     * @param  string                              $path    The path of the file.
     * @param  string|resource|StreamInterface     $content The content to be uploaded.
     * @param  Range                               $range   The range in the file to
     *                                                      be put.
     * @param  FileModels\PutFileRangeOptions|null $options The optional parameters.
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/put-range
     */
    public function putFileRange(
        string $share,
        string $path,
        $content,
        Range $range,
        ?FileModels\PutFileRangeOptions $options = null
    ): void;

    /**
     * Creates promise to write range of bytes to a file. Range can be at most
     * 4MB in length.
     *
     * @param  string                              $share   The share name.
     * @param  string                              $path    The path of the file.
     * @param  string|resource|StreamInterface     $content The content to be uploaded.
     * @param  Range                               $range   The range in the file to
     *                                                      be put.
     * @param  FileModels\PutFileRangeOptions|null $options The optional parameters.
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/put-range
     *
     */
    public function putFileRangeAsync(
        string $share,
        string $path,
        $content,
        Range $range,
        ?FileModels\PutFileRangeOptions $options = null
    ): PromiseInterface;

    /**
     * Creates a file from a provided content.
     *
     * @param  string                            $share   the share name
     * @param  string                            $path    the path of the file
     * @param  StreamInterface|resource|string   $content the content used to
     *                                                    create the file
     * @param  FileModels\CreateFileFromContentOptions|null
     *                                           $options optional parameters
     *
     */
    public function createFileFromContent(
        string $share,
        string $path,
        $content,
        ?FileModels\CreateFileFromContentOptions $options = null
    ): void;

    /**
     * Creates a promise to create a file from a provided content.
     *
     * @param  string                            $share   the share name
     * @param  string                            $path    the path of the file
     * @param  StreamInterface|resource|string   $content the content used to
     *                                                    create the file
     * @param  FileModels\CreateFileFromContentOptions|null
     *                                           $options optional parameters
     *
     */
    public function createFileFromContentAsync(
        string $share,
        string $path,
        $content,
        ?FileModels\CreateFileFromContentOptions $options = null
    ): PromiseInterface;

    /**
     * Clears range of bytes of a file. If the specified range is not 512-byte
     * aligned, the operation will write zeros to the start or end of the range
     * that is not 512-byte aligned and free the rest of the range inside that
     * is 512-byte aligned.
     *
     * @param  string                             $share   The share name.
     * @param  string                             $path    The path of the file.
     * @param  Range                              $range   The range in the file to
     *                                                     be cleared.
     * @param  FileModels\FileServiceOptions|null $options The optional parameters.
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/put-range
     */
    public function clearFileRange(
        string $share,
        string $path,
        Range $range,
        ?FileModels\FileServiceOptions $options = null
    ): void;

    /**
     * Creates promise to clear range of bytes of a file. If the specified range
     * is not 512-byte aligned, the operation will write zeros to the start or
     * end of the range that is not 512-byte aligned and free the rest of the
     * range inside that is 512-byte aligned.
     *
     * @param  string                             $share   The share name.
     * @param  string                             $path    The path of the file.
     * @param  Range                              $range   The range in the file to
     *                                                     be cleared.
     * @param  FileModels\FileServiceOptions|null $options The optional parameters.
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/put-range
     *
     */
    public function clearFileRangeAsync(
        string $share,
        string $path,
        Range $range,
        ?FileModels\FileServiceOptions $options = null
    ): PromiseInterface;

    /**
     * Lists range of bytes of a file.
     *
     * @param  string                             $share   The share name.
     * @param  string                             $path    The path of the file.
     * @param  Range                              $range   The range in the file to
     *                                                     be listed.
     * @param  FileModels\FileServiceOptions|null $options The optional parameters.
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/list-ranges
     */
    public function listFileRange(
        string $share,
        string $path,
        ?Range $range = null,
        ?FileModels\FileServiceOptions $options = null
    ): FileModels\ListFileRangesResult;

    /**
     * Creates promise to list range of bytes of a file.
     *
     * @param  string                             $share   The share name.
     * @param  string                             $path    The path of the file.
     * @param  Range                              $range   The range in the file to
     *                                                     be listed.
     * @param  FileModels\FileServiceOptions|null $options The optional parameters.
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/list-ranges
     *
     */
    public function listFileRangeAsync(
        string $share,
        string $path,
        ?Range $range = null,
        ?FileModels\FileServiceOptions $options = null
    ): PromiseInterface;

    /**
     * Informs server to copy file from $sourcePath to $path.
     * To copy a file to another file within the same storage account, you may
     * use Shared Key to authenticate the source file. If you are copying a file
     * from another storage account, or if you are copying a blob from the same
     * storage account or another storage account, then you must authenticate
     * the source file or blob using a shared access signature. If the source is
     * a public blob, no authentication is required to perform the copy
     * operation.
     * Here are some examples of source object URLs:
     * https://myaccount.file.core.windows.net/myshare/mydirectorypath/myfile
     * https://myaccount.blob.core.windows.net/mycontainer/myblob?sastoken
     *
     * @param  string                             $share      The share name.
     * @param  string                             $path       The path of the file.
     * @param  string                             $sourcePath The path of the source.
     * @param  array                              $metadata   The metadata of
     *                                                        the file. If
     *                                                        specified, source
     *                                                        metadata will not
     *                                                        be copied.
     * @param  FileModels\FileServiceOptions|null $options    The optional parameters.
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/copy-file
     */
    public function copyFile(
        string $share,
        string $path,
        string $sourcePath,
        array $metadata = [],
        ?FileModels\FileServiceOptions $options = null
    ): FileModels\CopyFileResult;

    /**
     * Creates promise to inform server to copy file from $sourcePath to $path.
     *
     * To copy a file to another file within the same storage account, you may
     * use Shared Key to authenticate the source file. If you are copying a file
     * from another storage account, or if you are copying a blob from the same
     * storage account or another storage account, then you must authenticate
     * the source file or blob using a shared access signature. If the source is
     * a public blob, no authentication is required to perform the copy
     * operation.
     * Here are some examples of source object URLs:
     * https://myaccount.file.core.windows.net/myshare/mydirectorypath/myfile
     * https://myaccount.blob.core.windows.net/mycontainer/myblob?sastoken
     *
     * @param  string                             $share      The share name.
     * @param  string                             $path       The path of the file.
     * @param  string                             $sourcePath The path of the source.
     * @param  array                              $metadata   The metadata of
     *                                                        the file. If
     *                                                        specified, source
     *                                                        metadata will not
     *                                                        be copied.
     * @param  FileModels\FileServiceOptions|null $options    The optional parameters.
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/copy-file
     *
     */
    public function copyFileAsync(
        string $share,
        string $path,
        string $sourcePath,
        array $metadata = [],
        ?FileModels\FileServiceOptions $options = null
    ): PromiseInterface;

    /**
     * Abort a file copy operation
     *
     * @param string                             $share   name of the share
     * @param string                             $path    path of the file
     * @param string                             $copyID  copy operation identifier.
     * @param FileModels\FileServiceOptions|null $options optional parameters
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/abort-copy-file
     */
    public function abortCopy(
        string $share,
        string $path,
        string $copyID,
        ?FileModels\FileServiceOptions $options = null
    ): void;

    /**
     * Creates promise to abort a file copy operation
     *
     * @param string                             $share   name of the share
     * @param string                             $path    path of the file
     * @param string                             $copyID  copy operation identifier.
     * @param FileModels\FileServiceOptions|null $options optional parameters
     *
     * @see https://docs.microsoft.com/en-us/rest/api/storageservices/abort-copy-file
     */
    public function abortCopyAsync(
        string $share,
        string $path,
        string $copyID,
        ?FileModels\FileServiceOptions $options = null
    ): PromiseInterface;

}
