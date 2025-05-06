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

/**
 * Holds container ACL
 *
 * @category  Microsoft
 * @package   MicrosoftAzure\Storage\Blob\Models
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */
class GetContainerACLResult
{

    private $containerACL;

    private $lastModified;

    private $etag;

    /**
     * Parses the given array into signed identifiers
     *
     * @param string    $publicAccess container public access
     * @param string    $etag         container etag
     * @param \DateTime $lastModified last modification date
     * @param array     $parsed       parsed response into array
     * representation
     *
     * @internal
     *
     */
    public static function create(
        string $publicAccess,
        string $etag,
        DateTime $lastModified,
        ?array $parsed = null
    ): self {
        $result = new GetContainerAclResult();
        $result->setETag($etag);
        $result->setLastModified($lastModified);
        $acl = ContainerACL::create($publicAccess, $parsed);
        $result->setContainerAcl($acl);

        return $result;
    }

    /**
     * Gets container ACL
     *
     */
    public function getContainerAcl(): ContainerACL
    {
        return $this->containerACL;
    }

    /**
     * Sets container ACL
     *
     * @param ContainerACL $containerACL value.
     *
     */
    protected function setContainerAcl(ContainerACL $containerACL): void
    {
        $this->containerACL = $containerACL;
    }

    /**
     * Gets container lastModified.
     *
     * @return \DateTime.
     */
    public function getLastModified()
    {
        return $this->lastModified;
    }

    /**
     * Sets container lastModified.
     *
     * @param \DateTime $lastModified value.
     *
     */
    protected function setLastModified(DateTime $lastModified): void
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
    protected function setETag(string $etag): void
    {
        $this->etag = $etag;
    }

}
