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

use MicrosoftAzure\Storage\Table\Internal\AcceptOptionTrait;

/**
 * Optional parameters for queryTables wrapper.
 *
 * @category  Microsoft
 * @package   MicrosoftAzure\Storage\Table\Models
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */
class QueryTablesOptions extends TableServiceOptions
{

    use TableContinuationTokenTrait;
    use AcceptOptionTrait;

    private $query;

    private $prefix;

    /**
     * Constructs new QueryTablesOptions object.
     */
    public function __construct()
    {
        parent::__construct();

        $this->query = new Query();
    }

    /**
     * Gets prefix
     *
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * Sets prefix
     *
     * @param string $prefix value
     *
     */
    public function setPrefix(string $prefix): void
    {
        $this->prefix = $prefix;
    }

    /**
     * Gets top.
     *
     */
    public function getTop(): int
    {
        return $this->query->getTop();
    }

    /**
     * Sets top.
     *
     * @param int $top value.
     *
     */
    public function setTop(int $top): void
    {
        $this->query->setTop($top);
    }

    /**
     * Gets query.
     *
     */
    public function getQuery(): Query
    {
        return $this->query;
    }

    /**
     * Gets filter.
     *
     */
    public function getFilter(): Filters\Filter
    {
        return $this->query->getFilter();
    }

    /**
     * Sets filter.
     *
     * @param Filters\Filter $filter value.
     *
     */
    public function setFilter(Filters\Filter $filter): void
    {
        $this->query->setFilter($filter);
    }

    /**
     * Sets selectFields.
     *
     * You can either use this individual function or use setQuery to set the
     * whole query object.
     *
     * @param array $selectFields value.
     *
     */
    public function setSelectFields(?array $selectFields = null): void
    {
        $this->query->setSelectFields($selectFields);
    }

}
