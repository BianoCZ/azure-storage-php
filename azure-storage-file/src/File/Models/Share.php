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

use MicrosoftAzure\Storage\Common\Internal\Utilities;
use MicrosoftAzure\Storage\File\Internal\FileResources as Resources;

/**
 * WindowsAzure share object.
 *
 * @category  Microsoft
 * @package   MicrosoftAzure\Storage\File\Models
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2017 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */
class Share
{

    private $name;

    private $metadata;

    private $properties;

    /**
     * Creates an instance with given response array.
     *
     * @param  array  $parsedResponse The response array.
     *
     */
    public static function create(array $parsedResponse): Share
    {
        $result = new Share();
        $result->setName($parsedResponse[Resources::QP_NAME]);
        $result->setMetadata(
            Utilities::tryGetValue($parsedResponse, Resources::QP_METADATA, [])
        );
        $result->setProperties(ShareProperties::create(
            $parsedResponse[Resources::QP_PROPERTIES]
        ));
        return $result;
    }

    /**
     * Gets share name.
     *
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets share name.
     *
     * @param string $name value.
     *
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Gets share metadata.
     *
     */
    public function getMetadata(): array
    {
        return $this->metadata;
    }

    /**
     * Sets share metadata.
     *
     * @param array $metadata value.
     *
     */
    public function setMetadata(?array $metadata = null): void
    {
        $this->metadata = $metadata;
    }

    /**
     * Gets share properties
     *
     */
    public function getProperties(): ShareProperties
    {
        return $this->properties;
    }

    /**
     * Sets share properties
     *
     * @param ShareProperties $properties share properties
     *
     */
    public function setProperties(ShareProperties $properties): void
    {
        $this->properties = $properties;
    }

}
