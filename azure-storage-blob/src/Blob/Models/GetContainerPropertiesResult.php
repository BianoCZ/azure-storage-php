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
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */

namespace MicrosoftAzure\Storage\Blob\Models;

use MicrosoftAzure\Storage\Blob\Internal\BlobResources as Resources;
use MicrosoftAzure\Storage\Common\Internal\MetadataTrait;
use MicrosoftAzure\Storage\Common\Internal\Utilities;
use MicrosoftAzure\Storage\Common\Internal\Validate;

/**
 * Holds result of getContainerProperties and getContainerMetadata
 *
 * @category  Microsoft
 * @package   MicrosoftAzure\Storage\Blob\Models
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */
class GetContainerPropertiesResult
{

    use MetadataTrait;

    private $leaseStatus;

    private $leaseState;

    private $leaseDuration;

    private $publicAccess;

    /**
     * Gets blob leaseStatus.
     *
     */
    public function getLeaseStatus(): string
    {
        return $this->leaseStatus;
    }

    /**
     * Sets blob leaseStatus.
     *
     * @param string $leaseStatus value.
     *
     */
    public function setLeaseStatus(string $leaseStatus): void
    {
        $this->leaseStatus = $leaseStatus;
    }

    /**
     * Gets blob lease state.
     *
     */
    public function getLeaseState(): string
    {
        return $this->leaseState;
    }

    /**
     * Sets blob lease state.
     *
     * @param string $leaseState value.
     *
     */
    public function setLeaseState(string $leaseState): void
    {
        $this->leaseState = $leaseState;
    }

    /**
     * Gets blob lease duration.
     *
     */
    public function getLeaseDuration(): string
    {
        return $this->leaseDuration;
    }

    /**
     * Sets blob leaseStatus.
     *
     * @param string $leaseDuration value.
     *
     */
    public function setLeaseDuration(string $leaseDuration): void
    {
        $this->leaseDuration = $leaseDuration;
    }

    /**
     * Gets container publicAccess.
     *
     */
    public function getPublicAccess(): string
    {
        return $this->publicAccess;
    }

    /**
     * Sets container publicAccess.
     *
     * @param string $publicAccess value.
     *
     */
    public function setPublicAccess(string $publicAccess): void
    {
        Validate::isTrue(
            PublicAccessType::isValid($publicAccess),
            Resources::INVALID_BLOB_PAT_MSG
        );
        $this->publicAccess = $publicAccess;
    }

    /**
     * Create an instance using the response headers from the API call.
     *
     * @param  array  $responseHeaders The array contains all the response headers
     *
     * @internal
     *
     */
    public static function create(array $responseHeaders): GetContainerPropertiesResult
    {
        $result = static::createMetadataResult($responseHeaders);

        $result->setLeaseStatus(Utilities::tryGetValueInsensitive(
            Resources::X_MS_LEASE_STATUS,
            $responseHeaders
        ));
        $result->setLeaseState(Utilities::tryGetValueInsensitive(
            Resources::X_MS_LEASE_STATE,
            $responseHeaders
        ));
        $result->setLeaseDuration(Utilities::tryGetValueInsensitive(
            Resources::X_MS_LEASE_DURATION,
            $responseHeaders
        ));
        $result->setPublicAccess(Utilities::tryGetValueInsensitive(
            Resources::X_MS_BLOB_PUBLIC_ACCESS,
            $responseHeaders
        ));

        return $result;
    }

}
