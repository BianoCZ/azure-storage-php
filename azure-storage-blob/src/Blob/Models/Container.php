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

/**
 * WindowsAzure container object.
 *
 * @category  Microsoft
 * @package   MicrosoftAzure\Storage\Blob\Models
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */
class Container
{

    private $_name;

    private $_url;

    private $_metadata;

    private $_properties;

    /**
     * Gets container name.
     *
     */
    public function getName(): string
    {
        return $this->_name;
    }

    /**
     * Sets container name.
     *
     * @param string $name value.
     *
     */
    public function setName(string $name): void
    {
        $this->_name = $name;
    }

    /**
     * Gets container url.
     *
     */
    public function getUrl(): string
    {
        return $this->_url;
    }

    /**
     * Sets container url.
     *
     * @param string $url value.
     *
     */
    public function setUrl(string $url): void
    {
        $this->_url = $url;
    }

    /**
     * Gets container metadata.
     *
     */
    public function getMetadata(): array
    {
        return $this->_metadata;
    }

    /**
     * Sets container metadata.
     *
     * @param array $metadata value.
     *
     */
    public function setMetadata(?array $metadata = null): void
    {
        $this->_metadata = $metadata;
    }

    /**
     * Gets container properties
     *
     */
    public function getProperties(): ContainerProperties
    {
        return $this->_properties;
    }

    /**
     * Sets container properties
     *
     * @param ContainerProperties $properties container properties
     *
     */
    public function setProperties(ContainerProperties $properties): void
    {
        $this->_properties = $properties;
    }

}
