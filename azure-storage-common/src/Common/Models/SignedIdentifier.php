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
 * @package   MicrosoftAzure\Storage\Common\Models
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */

namespace MicrosoftAzure\Storage\Common\Models;

use MicrosoftAzure\Storage\Common\Internal\Resources;

/**
 * Holds signed identifiers.
 *
 * @category  Microsoft
 * @package   MicrosoftAzure\Storage\Common\Models
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */
class SignedIdentifier
{

    private $id;

    private $accessPolicy;

    /**
     * Constructor
     *
     * @param string            $id           The id of this signed identifier.
     * @param AccessPolicy|null $accessPolicy The access policy.
     */
    public function __construct(string $id = '', ?AccessPolicy $accessPolicy = null)
    {
        $this->setId($id);
        $this->setAccessPolicy($accessPolicy);
    }

    /**
     * Gets id.
     *
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Sets id.
     *
     * @param string $id value.
     *
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * Gets accessPolicy.
     *
     */
    public function getAccessPolicy(): AccessPolicy
    {
        return $this->accessPolicy;
    }

    /**
     * Sets accessPolicy.
     *
     * @param AccessPolicy|null $accessPolicy value.
     *
     */
    public function setAccessPolicy(?AccessPolicy $accessPolicy = null): void
    {
        $this->accessPolicy = $accessPolicy;
    }

    /**
     * Converts this current object to XML representation.
     *
     * @internal
     *
     */
    public function toArray(): array
    {
        $array = [];
        $accessPolicyArray = [];
        $accessPolicyArray[Resources::XTAG_SIGNED_ID] = $this->getId();
        $accessPolicyArray[Resources::XTAG_ACCESS_POLICY] =
            $this->getAccessPolicy()->toArray();
        $array[Resources::XTAG_SIGNED_IDENTIFIER] = $accessPolicyArray;

        return $array;
    }

}
