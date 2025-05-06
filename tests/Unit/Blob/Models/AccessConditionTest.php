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
 * @package   MicrosoftAzure\Storage\Tests\Unit\Blob\Models
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */

namespace MicrosoftAzure\Storage\Tests\Unit\Blob\Models;

use DateTime;
use MicrosoftAzure\Storage\Blob\Models\AccessCondition;
use MicrosoftAzure\Storage\Common\Internal\Resources;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for class AccessCondition
 *
 * @category  Microsoft
 * @package   MicrosoftAzure\Storage\Tests\Unit\Blob\Models
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */
class AccessConditionTest extends TestCase
{
    public function testConstruct(): void
    {
        // Setup
        $expectedHeaderType = Resources::IF_MATCH;
        $expectedValue = '0x8CAFB82EFF70C46';

        // Test
        $actual = AccessCondition::ifMatch($expectedValue);

        // Assert
        $this->assertEquals($expectedHeaderType, $actual->getHeader());
        $this->assertEquals($expectedValue, $actual->getValue());
    }

    public function testNone(): void
    {
        // Setup
        $expectedHeader = Resources::EMPTY_STRING;
        $expectedValue = null;

        // Test
        $actual = AccessCondition::none();

        // Assert
        $this->assertEquals($expectedHeader, $actual->getHeader());
        $this->assertEquals($expectedValue, $actual->getValue());
    }

    public function testIfModifiedSince(): void
    {
        // Setup
        $expectedHeader = Resources::IF_MODIFIED_SINCE;
        $expectedValue = new DateTime('Sun, 25 Sep 2011 00:42:49 GMT');

        // Test
        $actual = AccessCondition::ifModifiedSince($expectedValue);

        // Assert
        $this->assertEquals($expectedHeader, $actual->getHeader());
        $this->assertEquals($expectedValue, $actual->getValue());
    }

    public function testIfMatch(): void
    {
        // Setup
        $expectedHeader = Resources::IF_MATCH;
        $expectedValue = '0x8CAFB82EFF70C46';

        // Test
        $actual = AccessCondition::ifMatch($expectedValue);

        // Assert
        $this->assertEquals($expectedHeader, $actual->getHeader());
        $this->assertEquals($expectedValue, $actual->getValue());
    }

    public function testIfNoneMatch(): void
    {
        // Setup
        $expectedHeader = Resources::IF_NONE_MATCH;
        $expectedValue = '0x8CAFB82EFF70C46';

        // Test
        $actual = AccessCondition::ifNoneMatch($expectedValue);

        // Assert
        $this->assertEquals($expectedHeader, $actual->getHeader());
        $this->assertEquals($expectedValue, $actual->getValue());
    }

    public function testIfNotModifiedSince(): void
    {
        // Setup
        $expectedHeader = Resources::IF_UNMODIFIED_SINCE;
        $expectedValue = new DateTime('Sun, 25 Sep 2011 00:42:49 GMT');

        // Test
        $actual = AccessCondition::ifNotModifiedSince($expectedValue);

        // Assert
        $this->assertEquals($expectedHeader, $actual->getHeader());
        $this->assertEquals($expectedValue, $actual->getValue());
    }

    public function testIsValidWithValid(): void
    {
        // Test
        $actual = AccessCondition::isValid(Resources::IF_MATCH);

        // Assert
        $this->assertTrue($actual);
    }

    public function testIsValidWithInvalid(): void
    {
        // Test
        $actual = AccessCondition::isValid('1234');

        // Assert
        $this->assertFalse($actual);
    }

}
