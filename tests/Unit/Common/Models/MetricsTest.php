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
use MicrosoftAzure\Storage\Common\Models\Metrics;
use MicrosoftAzure\Storage\Common\Models\RetentionPolicy;
use MicrosoftAzure\Storage\Tests\Framework\TestResources;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for class Metrics
 *
 * @category  Microsoft
 * @package   MicrosoftAzure\Storage\Tests\Unit\Common\Models
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */
class MetricsTest extends TestCase
{
    public function testCreate(): void
    {
        // Setup
        $sample = TestResources::getServicePropertiesSample();

        // Test
        $actual = Metrics::create($sample['HourMetrics']);

        // Assert
        $this->assertEquals(Utilities::toBoolean($sample['HourMetrics']['Enabled']), $actual->getEnabled());
        $this->assertEquals(Utilities::toBoolean($sample['HourMetrics']['IncludeAPIs']), $actual->getIncludeAPIs());
        $this->assertEquals(RetentionPolicy::create($sample['HourMetrics']['RetentionPolicy']), $actual->getRetentionPolicy());
        $this->assertEquals($sample['HourMetrics']['Version'], $actual->getVersion());
    }

    public function testGetRetentionPolicy(): void
    {
        // Setup
        $sample = TestResources::getServicePropertiesSample();
        $metrics = new Metrics();
        $expected = RetentionPolicy::create($sample['HourMetrics']['RetentionPolicy']);
        $metrics->setRetentionPolicy($expected);

        // Test
        $actual = $metrics->getRetentionPolicy();

        // Assert
        $this->assertEquals($expected, $actual);
    }

    public function testSetRetentionPolicy(): void
    {
        // Setup
        $sample = TestResources::getServicePropertiesSample();
        $metrics = new Metrics();
        $expected = RetentionPolicy::create($sample['HourMetrics']['RetentionPolicy']);

        // Test
        $metrics->setRetentionPolicy($expected);

        // Assert
        $actual = $metrics->getRetentionPolicy();
        $this->assertEquals($expected, $actual);
    }

    public function testGetVersion(): void
    {
        // Setup
        $sample = TestResources::getServicePropertiesSample();
        $metrics = new Metrics();
        $expected = $sample['HourMetrics']['Version'];
        $metrics->setVersion($expected);

        // Test
        $actual = $metrics->getVersion();

        // Assert
        $this->assertEquals($expected, $actual);
    }

    public function testSetVersion(): void
    {
        // Setup
        $sample = TestResources::getServicePropertiesSample();
        $metrics = new Metrics();
        $expected = $sample['HourMetrics']['Version'];

        // Test
        $metrics->setVersion($expected);

        // Assert
        $actual = $metrics->getVersion();
        $this->assertEquals($expected, $actual);
    }

    public function testGetEnabled(): void
    {
        // Setup
        $sample = TestResources::getServicePropertiesSample();
        $metrics = new Metrics();
        $expected = Utilities::toBoolean($sample['HourMetrics']['Enabled']);
        $metrics->setEnabled($expected);

        // Test
        $actual = $metrics->getEnabled();

        // Assert
        $this->assertEquals($expected, $actual);
    }

    public function testSetEnabled(): void
    {
        // Setup
        $sample = TestResources::getServicePropertiesSample();
        $metrics = new Metrics();
        $expected = Utilities::toBoolean($sample['HourMetrics']['Enabled']);

        // Test
        $metrics->setEnabled($expected);

        // Assert
        $actual = $metrics->getEnabled();
        $this->assertEquals($expected, $actual);
    }

    public function testGetIncludeAPIs(): void
    {
        // Setup
        $sample = TestResources::getServicePropertiesSample();
        $metrics = new Metrics();
        $expected = Utilities::toBoolean($sample['HourMetrics']['IncludeAPIs']);
        $metrics->setIncludeAPIs($expected);

        // Test
        $actual = $metrics->getIncludeAPIs();

        // Assert
        $this->assertEquals($expected, $actual);
    }

    public function testSetIncludeAPIs(): void
    {
        // Setup
        $sample = TestResources::getServicePropertiesSample();
        $metrics = new Metrics();
        $expected = Utilities::toBoolean($sample['HourMetrics']['IncludeAPIs']);

        // Test
        $metrics->setIncludeAPIs($expected);

        // Assert
        $actual = $metrics->getIncludeAPIs();
        $this->assertEquals($expected, $actual);
    }

    public function testToArray(): void
    {
        // Setup
        $sample = TestResources::getServicePropertiesSample();
        $metrics = Metrics::create($sample['HourMetrics']);
        $expected = [
            'Version'         => $sample['HourMetrics']['Version'],
            'Enabled'         => $sample['HourMetrics']['Enabled'],
            'IncludeAPIs'     => $sample['HourMetrics']['IncludeAPIs'],
            'RetentionPolicy' => $metrics->getRetentionPolicy()->toArray(),
        ];

        // Test
        $actual = $metrics->toArray();

        // Assert
        $this->assertEquals($expected, $actual);
    }

    public function testToArrayWithNotEnabled(): void
    {
        // Setup
        $sample = TestResources::getServicePropertiesSample();
        $sample['HourMetrics']['Enabled'] = 'false';
        $metrics = Metrics::create($sample['HourMetrics']);
        $expected = [
            'Version'         => $sample['HourMetrics']['Version'],
            'Enabled'         => $sample['HourMetrics']['Enabled'],
            'RetentionPolicy' => $metrics->getRetentionPolicy()->toArray(),
        ];

        // Test
        $actual = $metrics->toArray();

        // Assert
        $this->assertEquals($expected, $actual);
    }

}
