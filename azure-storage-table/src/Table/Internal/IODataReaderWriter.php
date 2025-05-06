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
 * @package   MicrosoftAzure\Storage\Table\Internal
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */

namespace MicrosoftAzure\Storage\Table\Internal;

use MicrosoftAzure\Storage\Table\Models\Entity;

/**
 * Defines how to serialize and unserialize table wrapper JSON
 *
 * @ignore
 * @category  Microsoft
 * @package   MicrosoftAzure\Storage\Table\Internal
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */
interface IODataReaderWriter
{
    /**
     * Constructs JSON representation for table entry.
     *
     * @param string $name The name of the table.
     *
     */
    public function getTable(string $name): string;

    /**
     * Parses one table entry.
     *
     * @param string $body The HTTP response body.
     *
     */
    public function parseTable(string $body): string;

    /**
     * Constructs array of tables from HTTP response body.
     *
     * @param string $body The HTTP response body.
     *
     */
    public function parseTableEntries(string $body): array;

    /**
     * Constructs JSON representation for entity.
     *
     * @param \MicrosoftAzure\Storage\Table\Models\Entity $entity The entity instance.
     *
     */
    public function getEntity(Entity $entity): string;

    /**
     * Constructs entity from HTTP response body.
     *
     * @param string $body The HTTP response body.
     *
     */
    public function parseEntity(string $body): Entity;

    /**
     * Constructs array of entities from HTTP response body.
     *
     * @param string $body The HTTP response body.
     *
     */
    public function parseEntities(string $body): array;

}
