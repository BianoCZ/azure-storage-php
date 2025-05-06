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
 * @package   MicrosoftAzure\Storage\Table\Models
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */

namespace MicrosoftAzure\Storage\Table\Models;

use DateTime;
use MicrosoftAzure\Storage\Common\Internal\Utilities;
use MicrosoftAzure\Storage\Common\Internal\Validate;
use MicrosoftAzure\Storage\Table\Internal\TableResources as Resources;
use Throwable;
use function is_null;
use function sprintf;

/**
 * Represents entity object used in tables
 *
 * @category  Microsoft
 * @package   MicrosoftAzure\Storage\Table\Models
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */
class Entity
{

    private $_etag;

    private $_properties;

    /**
     * Validates if properties is valid or not.
     *
     * @param mixed $properties The properties array.
     *
     */
    private function _validateProperties(mixed $properties): void
    {
        Validate::isArray($properties, 'entity properties');

        foreach ($properties as $key => $value) {
            Validate::canCastAsString($key, 'key');
            Validate::isTrue(
                $value instanceof Property,
                Resources::INVALID_PROP_MSG
            );
            Validate::isTrue(
                EdmType::validateEdmValue(
                    $value->getEdmType(),
                    $value->getValue(),
                    $condition
                ),
                sprintf(Resources::INVALID_PROP_VAL_MSG, $key, $condition)
            );
        }
    }

    /**
     * Gets property value and if the property name is not found return null.
     *
     * @param string $name The property name.
     *
     */
    public function getPropertyValue(string $name): mixed
    {
        $p = Utilities::tryGetValue($this->_properties, $name);
        return is_null($p) ? null : $p->getValue();
    }

    /**
     * Sets property value.
     *
     * Note that if the property doesn't exist, it doesn't add it. Use addProperty
     * to add new properties.
     *
     * @param string $name  The property name.
     * @param mixed  $value The property value.
     *
     */
    public function setPropertyValue(string $name, mixed $value): mixed
    {
        $p = Utilities::tryGetValue($this->_properties, $name);
        if (!is_null($p)) {
            $p->setValue($value);
        }
    }

    /**
     * Gets entity etag.
     *
     */
    public function getETag(): string
    {
        return $this->_etag;
    }

    /**
     * Sets entity etag.
     *
     * @param string $etag The entity ETag value.
     *
     */
    public function setETag(string $etag): void
    {
        $this->_etag = $etag;
    }

    /**
     * Gets entity PartitionKey.
     *
     */
    public function getPartitionKey(): string
    {
        return $this->getPropertyValue('PartitionKey');
    }

    /**
     * Sets entity PartitionKey.
     *
     * @param string $partitionKey The entity PartitionKey value.
     *
     */
    public function setPartitionKey(string $partitionKey): void
    {
        $this->addProperty('PartitionKey', EdmType::STRING, $partitionKey);
    }

    /**
     * Gets entity RowKey.
     *
     */
    public function getRowKey(): string
    {
        return $this->getPropertyValue('RowKey');
    }

    /**
     * Sets entity RowKey.
     *
     * @param string $rowKey The entity RowKey value.
     *
     */
    public function setRowKey(string $rowKey): void
    {
        $this->addProperty('RowKey', EdmType::STRING, $rowKey);
    }

    /**
     * Gets entity Timestamp.
     *
     */
    public function getTimestamp(): DateTime
    {
        return $this->getPropertyValue('Timestamp');
    }

    /**
     * Sets entity Timestamp.
     *
     * @param \DateTime $timestamp The entity Timestamp value.
     *
     */
    public function setTimestamp(DateTime $timestamp): void
    {
        $this->addProperty('Timestamp', EdmType::DATETIME, $timestamp);
    }

    /**
     * Gets the entity properties array.
     *
     */
    public function getProperties(): array
    {
        return $this->_properties;
    }

    /**
     * Sets the entity properties array.
     *
     * @param array $properties The entity properties.
     *
     */
    public function setProperties(array $properties): void
    {
        $this->_validateProperties($properties);
        $this->_properties = $properties;
    }

    /**
     * Gets property object from the entity properties.
     *
     * @param string $name The property name.
     *
     */
    public function getProperty(string $name): ?Property
    {
        return Utilities::tryGetValue($this->_properties, $name);
    }

    /**
     * Sets entity property.
     *
     * @param string   $name     The property name.
     * @param Property $property The property object.
     *
     */
    public function setProperty(string $name, Property $property): void
    {
        Validate::isTrue($property instanceof Property, Resources::INVALID_PROP_MSG);
        $this->_properties[$name] = $property;
    }

    /**
     * Creates new entity property.
     *
     * @param string $name     The property name.
     * @param string $edmType  The property edm type.
     * @param mixed  $value    The property value.
     * @param string $rawValue The raw value of the property.
     */
    public function addProperty(string $name, string $edmType, mixed $value, string $rawValue = ''): void
    {
        $p = new Property();
        $p->setEdmType($edmType);
        $p->setValue($value);
        $p->setRawValue($rawValue);
        $this->setProperty($name, $p);
    }

    /**
     * Checks if the entity object is valid or not.
     * Valid means the partition and row key exists for this entity along with the
     * timestamp.
     *
     * @param string &$msg The error message.
     *
     * @internal
     *
     */
    public function isValid(?string &$msg = null): bool
    {
        try {
            $this->_validateProperties($this->_properties);
        } catch (Throwable $exc) {
            $msg = $exc->getMessage();
            return false;
        }

        if (
            is_null($this->getPartitionKey())
            || is_null($this->getRowKey())
        ) {
            $msg = Resources::NULL_TABLE_KEY_MSG;
            return false;
        }

        return true;
    }

}
