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

/**
 * Query to be performed on a table
 *
 * @category  Microsoft
 * @package   MicrosoftAzure\Storage\Table\Models
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */
class Query
{

    private $_selectFields;

    private $_filter;

    private $_top;

    /**
     * Gets filter.
     *
     */
    public function getFilter(): Filters\Filter
    {
        return $this->_filter;
    }

    /**
     * Sets filter.
     *
     * @param Filters\Filter $filter value.
     *
     */
    public function setFilter(Filters\Filter $filter): void
    {
        $this->_filter = $filter;
    }

    /**
     * Gets top.
     *
     */
    public function getTop(): int
    {
        return $this->_top;
    }

    /**
     * Sets top.
     *
     * @param int $top value.
     *
     */
    public function setTop(int $top): void
    {
        $this->_top = $top;
    }

    /**
     * Adds a field to select fields.
     *
     * @param string $field The value of the field.
     *
     */
    public function addSelectField(string $field): void
    {
        $this->_selectFields[] = $field;
    }

    /**
     * Gets selectFields.
     *
     */
    public function getSelectFields(): array
    {
        return $this->_selectFields;
    }

    /**
     * Sets selectFields.
     *
     * @param array $selectFields value.
     *
     */
    public function setSelectFields(?array $selectFields = null): void
    {
        $this->_selectFields = $selectFields;
    }

}
