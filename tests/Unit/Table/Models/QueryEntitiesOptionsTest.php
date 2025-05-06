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
 * @package   MicrosoftAzure\Storage\Tests\Unit\Table\Models
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */

namespace MicrosoftAzure\Storage\Tests\Unit\Table\Models;

use MicrosoftAzure\Storage\Table\Models\EdmType;
use MicrosoftAzure\Storage\Table\Models\Filters\Filter;
use MicrosoftAzure\Storage\Table\Models\Query;
use MicrosoftAzure\Storage\Table\Models\QueryEntitiesOptions;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for class QueryEntitiesOptions
 *
 * @category  Microsoft
 * @package   MicrosoftAzure\Storage\Tests\Unit\Table\Models
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */
class QueryEntitiesOptionsTest extends TestCase
{
    public function testSetQuery(): void
    {
        // Setup
        $options = new QueryEntitiesOptions();
        $expected = new Query();

        // Test
        $options->setQuery($expected);

        // Assert
        $this->assertEquals($expected, $options->getQuery());
    }

    public function testSetNextPartitionKey(): void
    {
        // Setup
        $options = new QueryEntitiesOptions();
        $expected = 'parition';

        // Test
        $options->setNextPartitionKey($expected);

        // Assert
        $this->assertEquals($expected, $options->getNextPartitionKey());
    }

    public function testSetNextRowKey(): void
    {
        // Setup
        $options = new QueryEntitiesOptions();
        $expected = 'edelo';

        // Test
        $options->setNextRowKey($expected);

        // Assert
        $this->assertEquals($expected, $options->getNextRowKey());
    }

    public function testSetSelectFields(): void
    {
        // Setup
        $options = new QueryEntitiesOptions();
        $expected = ['customerId', 'customerName'];

        // Test
        $options->setSelectFields($expected);

        // Assert
        $this->assertEquals($expected, $options->getSelectFields());
    }

    public function testSetTop(): void
    {
        // Setup
        $options = new QueryEntitiesOptions();
        $expected = 123;

        // Test
        $options->setTop($expected);

        // Assert
        $this->assertEquals($expected, $options->getTop());
    }

    public function testSetFilter(): void
    {
        // Setup
        $options = new QueryEntitiesOptions();
        $expected = Filter::applyConstant('constValue', EdmType::STRING);

        // Test
        $options->setFilter($expected);

        // Assert
        $this->assertEquals($expected, $options->getFilter());
    }

    public function testAddSelectField(): void
    {
        // Setup
        $options = new QueryEntitiesOptions();
        $field = 'customerId';
        $expected = [$field];

        // Test
        $options->addSelectField($field);

        // Assert
        $this->assertEquals($expected, $options->getSelectFields());
    }

}
