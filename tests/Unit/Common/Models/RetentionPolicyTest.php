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
 * @package   MicrosoftAzure\Storage\Tests\Unit\Common\Models
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */

namespace MicrosoftAzure\Storage\Tests\Unit\Common\Models;

use MicrosoftAzure\Storage\Common\Internal\Utilities;
use MicrosoftAzure\Storage\Common\Models\RetentionPolicy;
use MicrosoftAzure\Storage\Tests\Framework\TestResources;
use PHPUnit\Framework\TestCase;
use function intval;

/**
 * Unit tests for class RetentionPolicy
 *
 * @category  Microsoft
 * @package   MicrosoftAzure\Storage\Tests\Unit\Common\Models
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */
class RetentionPolicyTest extends TestCase
{
    public function testCreate(): void
    {
        // Setup
        $sample = TestResources::getServicePropertiesSample();
        $expectedEnabled = Utilities::toBoolean($sample['Logging']['RetentionPolicy']['Enabled']);
        $expectedDays = intval($sample['Logging']['RetentionPolicy']['Days']);

        // Test
        $actual = RetentionPolicy::create($sample['Logging']['RetentionPolicy']);

        // Assert
        $this->assertEquals($expectedEnabled, $actual->getEnabled());
        $this->assertEquals($expectedDays, $actual->getDays());
    }

    public function testGetEnabled(): void
    {
        // Setup
        $sample = TestResources::getServicePropertiesSample();
        $retentionPolicy = new RetentionPolicy();
        $expected = Utilities::toBoolean($sample['Logging']['RetentionPolicy']['Enabled']);
        $retentionPolicy->setEnabled($expected);

        // Test
        $actual = $retentionPolicy->getEnabled();

        // Assert
        $this->assertEquals($expected, $actual);
    }

    public function testSetEnabled(): void
    {
        // Setup
        $sample = TestResources::getServicePropertiesSample();
        $retentionPolicy = new RetentionPolicy();
        $expected = Utilities::toBoolean($sample['Logging']['RetentionPolicy']['Enabled']);

        // Test
        $retentionPolicy->setEnabled($expected);

        // Assert
        $actual = $retentionPolicy->getEnabled();
        $this->assertEquals($expected, $actual);
    }

    public function testGetDays(): void
    {
        // Setup
        $sample = TestResources::getServicePropertiesSample();
        $retentionPolicy = new RetentionPolicy();
        $expected = intval($sample['Logging']['RetentionPolicy']['Days']);
        $retentionPolicy->setDays($expected);

        // Test
        $actual = $retentionPolicy->getDays();

        // Assert
        $this->assertEquals($expected, $actual);
    }

    public function testSetDays(): void
    {
        // Setup
        $sample = TestResources::getServicePropertiesSample();
        $retentionPolicy = new RetentionPolicy();
        $expected = intval($sample['Logging']['RetentionPolicy']['Days']);

        // Test
        $retentionPolicy->setDays($expected);

        // Assert
        $actual = $retentionPolicy->getDays();
        $this->assertEquals($expected, $actual);
    }

    public function testToArray(): void
    {
        // Setup
        $sample = TestResources::getServicePropertiesSample();
        $retentionPolicy = RetentionPolicy::create($sample['Logging']['RetentionPolicy']);
        $expected = [
            'Enabled' => $sample['Logging']['RetentionPolicy']['Enabled'],
            'Days'    => $sample['Logging']['RetentionPolicy']['Days'],
        ];

        // Test
        $actual = $retentionPolicy->toArray();

        // Assert
        $this->assertEquals($expected, $actual);
    }

    public function testToArrayWithoutDays(): void
    {
        // Setup
        $sample = TestResources::getServicePropertiesSample();
        $retentionPolicy = RetentionPolicy::create($sample['Logging']['RetentionPolicy']);
        $expected = ['Enabled' => $sample['Logging']['RetentionPolicy']['Enabled']];
        $retentionPolicy->setDays(null);

        // Test
        $actual = $retentionPolicy->toArray();

        // Assert
        $this->assertEquals($expected, $actual);
    }

}
