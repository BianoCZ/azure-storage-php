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
 * @package   MicrosoftAzure\Storage\Tests\Unit\Common\Internal
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */

namespace MicrosoftAzure\Storage\Tests\Unit\Common\Internal;

use DateTime;
use DateTimeImmutable;
use MicrosoftAzure\Storage\Common\Exceptions\InvalidArgumentTypeException;
use MicrosoftAzure\Storage\Common\Internal\Resources;
use MicrosoftAzure\Storage\Common\Internal\Utilities;
use MicrosoftAzure\Storage\Common\Internal\Validate;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use stdClass;
use function get_class;

/**
 * Unit tests for class ValidateTest
 *
 * @category  Microsoft
 * @package   MicrosoftAzure\Storage\Tests\Unit\Common\Internal
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */
class ValidateTest extends TestCase
{
    public function testIsArrayWithArray(): void
    {
        Validate::isArray([], 'array');

        $this->assertTrue(true);
    }

    public function testIsArrayWithNonArray(): void
    {
        $this->setExpectedException(get_class(new InvalidArgumentTypeException('')));
        Validate::isArray(123, 'array');
    }

    public function testIsStringWithString(): void
    {
        Validate::canCastAsString('I\'m a string', 'string');

        $this->assertTrue(true);
    }

    public function testIsStringWithNonString(): void
    {
        $this->setExpectedException(get_class(new InvalidArgumentTypeException('')));
        Validate::canCastAsString(new DateTime(), 'string');
    }

    public function testIsBooleanWithBoolean(): void
    {
        Validate::isBoolean(true);

        $this->assertTrue(true);
    }

    public function testIsIntegerWithInteger(): void
    {
        Validate::isInteger(123, 'integer');

        $this->assertTrue(true);
    }

    public function testIsIntegerWithNonInteger(): void
    {
        $this->setExpectedException(get_class(new InvalidArgumentTypeException('')));
        Validate::isInteger(new DateTime(), 'integer');
    }

    public function testIsTrueWithTrue(): void
    {
        Validate::isTrue(true, Resources::EMPTY_STRING);

        $this->assertTrue(true);
    }

    public function testIsTrueWithFalse(): void
    {
        $this->setExpectedException('\InvalidArgumentException');
        Validate::isTrue(false, Resources::EMPTY_STRING);
    }

    public function testIsDateWithDateTime(): void
    {
        $date = Utilities::rfc1123ToDateTime('Fri, 09 Oct 2009 21:04:30 GMT');
        Validate::isDate($date);

        $this->assertTrue(true);
    }

    public function testIsDateWithDateTimeImmutable(): void
    {
        $date = new DateTimeImmutable();
        Validate::isDate($date);

        $this->assertTrue(true);
    }

    public function testIsDateWithNonDate(): void
    {
        $this->setExpectedException(get_class(new InvalidArgumentTypeException('DateTime')));
        Validate::isDate('not date');
    }

    public function testNotNullOrEmptyWithNonEmpty(): void
    {
        Validate::notNullOrEmpty(1234, 'not null');
        Validate::notNullOrEmpty('0', 'not null');

        $this->assertTrue(true);
    }

    public function testNotNullOrEmptyWithEmpty(): void
    {
        $this->setExpectedException('\InvalidArgumentException');
        Validate::notNullOrEmpty(Resources::EMPTY_STRING, 'variable');
    }

    public function testNotNullWithNull(): void
    {
        $this->setExpectedException('\InvalidArgumentException');
        Validate::notNullOrEmpty(null, 'variable');
    }

    public function testIsInstanceOfStringPasses(): void
    {
        // Setup
        $value = 'testString';
        $stringObject = 'stringObject';

        // Test
        $result = Validate::isInstanceOf($value, $stringObject, 'value');

        // Assert
        $this->assertTrue($result);
    }

    public function testIsInstanceOfStringFail(): void
    {
        // Setup
        $this->setExpectedException('\InvalidArgumentException');
        $value = 'testString';
        $arrayObject = [];

        // Test
        $result = Validate::isInstanceOf($value, $arrayObject, 'value');

        // Assert
    }

    public function testIsInstanceOfArrayPasses(): void
    {
        // Setup
        $value = [];
        $arrayObject = [];

        // Test
        $result = Validate::isInstanceOf($value, $arrayObject, 'value');

        // Assert
        $this->assertTrue($result);
    }

    public function testIsInstanceOfArrayFail(): void
    {
        // Setup
        $this->setExpectedException('\InvalidArgumentException');
        $value = [];
        $stringObject = 'testString';

        // Test
        $result = Validate::isInstanceOf($value, $stringObject, 'value');

        // Assert
    }

    public function testIsInstanceOfIntPasses(): void
    {
        // Setup
        $value = 38;
        $intObject = 83;

        // Test
        $result = Validate::isInstanceOf($value, $intObject, 'value');

        // Assert
        $this->assertTrue($result);
    }

    public function testIsInstanceOfIntFail(): void
    {
        // Setup
        $this->setExpectedException('\InvalidArgumentException');
        $value = 38;
        $stringObject = 'testString';

        // Test
        $result = Validate::isInstanceOf($value, $stringObject, 'value');

        // Assert
    }

    public function testIsInstanceOfNullValue(): void
    {
        // Setup
        $value = null;
        $arrayObject = [];

        // Test
        $result = Validate::isInstanceOf($value, $arrayObject, 'value');

        // Assert
        $this->assertTrue($result);
    }

    public function testIsDoubleSuccess(): void
    {
        // Setup
        $value = 3.14159265;

        // Test
        Validate::isDouble($value, 'value');

        // Assert
        $this->assertTrue(true);
    }

    public function testIsDoubleFail(): void
    {
        // Setup
        $this->setExpectedException('\InvalidArgumentException');
        $value = 'testInvalidDoubleValue';

        // Test
        Validate::isDouble($value, 'value');

        // Assert
    }

    public function testGetValidateHostname(): void
    {
        // Test
        $function = Validate::getIsValidHostname();

        // Assert
        $this->assertInternalType('callable', $function);
    }

    public function testIsValidHostnamePass(): void
    {
        // Setup
        $value = 'test.com';

        // Test
        $result = Validate::isValidHostname($value);

        // Assert
        $this->assertTrue($result);
    }

    public function testIsValidHostnameNull(): void
    {
        // Setup
        $this->setExpectedException(get_class(new RuntimeException('')));
        $value = null;

        // Test
        $result = Validate::isValidHostname($value);

        // Assert
    }

    public function testIsValidHostnameInvalid(): void
    {
        // Setup
        $this->setExpectedException(get_class(new RuntimeException('')));
        $value = '.test';

        // Test
        $result = Validate::isValidHostname($value);

        // Assert
    }

    public function testGetValidateUri(): void
    {
        // Test
        $function = Validate::getIsValidUri();

        // Assert
        $this->assertInternalType('callable', $function);
    }

    public function testIsValidUriPass(): void
    {
        // Setup
        $value = 'http://test.com';

        // Test
        $result = Validate::isValidUri($value);

        // Assert
        $this->assertTrue($result);
    }

    public function testIsValidUriNull(): void
    {
        // Setup
        $this->setExpectedException(get_class(new RuntimeException('')));
        $value = null;

        // Test
        $result = Validate::isValidUri($value);

        // Assert
    }

    public function testIsValidUriNotUri(): void
    {
        // Setup
        $this->setExpectedException(get_class(new RuntimeException('')));
        $value = 'test string';

        // Test
        $result = Validate::isValidUri($value);

        // Assert
    }

    public function testIsObjectPass(): void
    {
        // Setup
        $value = new stdClass();

        // Test
        $result = Validate::isObject($value, 'value');

        // Assert
        $this->assertTrue($result);
    }

    public function testIsObjectNull(): void
    {
        // Setup
        $this->setExpectedException(get_class(new InvalidArgumentTypeException('')));
        $value = null;

        // Test
        $result = Validate::isObject($value, 'value');

        // Assert
    }

    public function testIsObjectNotObject(): void
    {
        // Setup
        $this->setExpectedException(get_class(new InvalidArgumentTypeException('')));
        $value = 'test string';

        // Test
        $result = Validate::isObject($value, 'value');

        // Assert
    }

    public function testIsAResourcesPasses(): void
    {
        // Setup
        $value = new Resources();
        $type = 'MicrosoftAzure\Storage\Common\Internal\Resources';

        // Test
        $result = Validate::isA($value, $type, 'value');

        // Assert
        $this->assertTrue($result);
    }

    public function testIsANull(): void
    {
        // Setup
        $this->setExpectedException('\InvalidArgumentException');
        $value = null;
        $type = 'MicrosoftAzure\Storage\Common\Internal\Resources';

        // Test
        $result = Validate::isA($value, $type, 'value');

        // Assert
    }

    public function testIsAInvalidClass(): void
    {
        // Setup
        $this->setExpectedException('\InvalidArgumentException');
        $value = new Resources();
        $type = 'Some\Invalid\Class';

        // Test
        $result = Validate::isA($value, $type, 'value');

        // Assert
    }

    public function testIsANotAClass(): void
    {
        // Setup
        $this->setExpectedException(get_class(new InvalidArgumentTypeException('')));
        $value = 'test string';
        $type = 'MicrosoftAzure\Storage\Common\Internal\Resources';

        // Test
        $result = Validate::isA($value, $type, 'value');

        // Assert
    }

    public function testIsDateStringValid(): void
    {
        // Setup
        $value = '2013-11-25';

        // Test
        $result = Validate::isDateString($value, 'name');

        // Assert
        $this->assertTrue($result);
    }

    public function testIsDateStringNotValid(): void
    {
        // Setup
        $this->setExpectedException('\InvalidArgumentException');
        $value = 'not a date';

        // Test
        $result = Validate::isDateString($value, 'name');

        // Assert
    }

}
