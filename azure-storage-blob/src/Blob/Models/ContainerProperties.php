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

use DateTime;
use MicrosoftAzure\Storage\Blob\Internal\BlobResources as Resources;
use MicrosoftAzure\Storage\Common\Internal\Validate;

/**
 * Holds container properties fields
 *
 * @category  Microsoft
 * @package   MicrosoftAzure\Storage\Blob\Models
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */
class ContainerProperties
{

    private $etag;

    private $lastModified;

    private $leaseDuration;

    private $leaseStatus;

    private $leaseState;

    private $publicAccess;

    /**
     * Gets container lastModified.
     *
     */
    public function getLastModified(): DateTime
    {
        return $this->lastModified;
    }

    /**
     * Sets container lastModified.
     *
     * @param \DateTime $lastModified value.
     *
     */
    public function setLastModified(DateTime $lastModified): void
    {
        $this->lastModified = $lastModified;
    }

    /**
     * Gets container etag.
     *
     */
    public function getETag(): string
    {
        return $this->etag;
    }

    /**
     * Sets container etag.
     *
     * @param string $etag value.
     *
     */
    public function setETag(string $etag): void
    {
        $this->etag = $etag;
    }

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

}
