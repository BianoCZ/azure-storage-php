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
 * @package   MicrosoftAzure\Storage\File\Models
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2017 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */

namespace MicrosoftAzure\Storage\File\Models;

use DateTime;

/**
 * Holds share ACL
 *
 * @category  Microsoft
 * @package   MicrosoftAzure\Storage\File\Models
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2017 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */
class GetShareACLResult
{

    private $shareACL;

    private $lastModified;

    private $etag;

    /**
     * Parses the given array into signed identifiers
     *
     * @param string    $etag         share etag
     * @param \DateTime $lastModified last modification date
     * @param array     $parsed       parsed response into array
     * representation
     *
     * @internal
     *
     */
    public static function create(
        string $etag,
        DateTime $lastModified,
        ?array $parsed = null
    ): self {
        $result = new GetShareAclResult();
        $result->setETag($etag);
        $result->setLastModified($lastModified);
        $acl = ShareACL::create($parsed);
        $result->setShareAcl($acl);

        return $result;
    }

    /**
     * Gets share ACL
     *
     */
    public function getShareAcl(): ShareACL
    {
        return $this->shareACL;
    }

    /**
     * Sets share ACL
     *
     * @param ShareACL $shareACL value.
     *
     */
    protected function setShareAcl(ShareACL $shareACL): void
    {
        $this->shareACL = $shareACL;
    }

    /**
     * Gets share lastModified.
     *
     * @return \DateTime.
     */
    public function getLastModified(): DateTime
    {
        return $this->lastModified;
    }

    /**
     * Sets share lastModified.
     *
     * @param \DateTime $lastModified value.
     *
     */
    protected function setLastModified(DateTime $lastModified): void
    {
        $this->lastModified = $lastModified;
    }

    /**
     * Gets share etag.
     *
     */
    public function getETag(): string
    {
        return $this->etag;
    }

    /**
     * Sets share etag.
     *
     * @param string $etag value.
     *
     */
    protected function setETag(string $etag): void
    {
        $this->etag = $etag;
    }

}
