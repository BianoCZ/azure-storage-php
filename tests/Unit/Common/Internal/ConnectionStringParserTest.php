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

use MicrosoftAzure\Storage\Common\Internal\ConnectionStringParser;
use PHPUnit\Framework\TestCase;
use function func_get_args;
use function func_num_args;

/**
 * Unit tests for class ConnectionStringParser
 *
 * @category  Microsoft
 * @package   MicrosoftAzure\Storage\Tests\Unit\Common\Internal
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */
class ConnectionStringParserTest extends TestCase
{
    private function _parseTest($connectionString): void
    {
        // Setup
        $arguments = func_get_args();
        $count = func_num_args();
        $expected = [];
        for ($i = 1; $i < $count; $i += 2) {
            $expected[$arguments[$i]] = $arguments[$i + 1];
        }

        // Test
        $actual = ConnectionStringParser::parseConnectionString('connectionString', $connectionString);

        // Assert
        $this->assertEquals($expected, $actual);
    }

    private function _parseTestFail($value): void
    {
        // Setup
        $this->setExpectedException('\RuntimeException');

        // Test
        ConnectionStringParser::parseConnectionString('connectionString', $value);
    }

    public function testKeyNames(): void
    {
        $this->_parseTest("a=b", "a", "b");
        $this->_parseTest(" a =b; c = d", "a", "b", "c", "d");
        $this->_parseTest("a b=c", "a b", "c");
        $this->_parseTest("'a b'=c", "a b", "c");
        $this->_parseTest("\"a b\"=c", "a b", "c");
        $this->_parseTest("\"a=b\"=c", "a=b", "c");
        $this->_parseTest("a=b=c", "a", "b=c");
        $this->_parseTest("'a='=b", "a=", "b");
        $this->_parseTest("\"a=\"=b", "a=", "b");
        $this->_parseTest("\"a'b\"=c", "a'b", "c");
        $this->_parseTest("'a\"b'=c", "a\"b", "c");
        $this->_parseTest("a'b=c", "a'b", "c");
        $this->_parseTest("a\"b=c", "a\"b", "c");
        $this->_parseTest("a'=b", "a'", "b");
        $this->_parseTest("a\"=b", "a\"", "b");
    }

    public function testAssignments(): void
    {
        $this->_parseTest("a=b", "a", "b");
        $this->_parseTest("a = b", "a", "b");
        $this->_parseTest("a==b", "a", "=b");
    }

    public function testValues(): void
    {
        $this->_parseTest("a=b", "a", "b");
        $this->_parseTest("a= b ", "a", "b");
        $this->_parseTest("a= b ;c= d;", "a", "b", "c", "d");
        $this->_parseTest("a=", "a", "");
        $this->_parseTest("a=;", "a", "");
        $this->_parseTest("a=;b=", "a", "", "b", "");
        $this->_parseTest("a==b", "a", "=b");
        $this->_parseTest("a=b=;c==d=", "a", "b=", "c", "=d=");
        $this->_parseTest("a='b c'", "a", "b c");
        $this->_parseTest("a=\"b c\"", "a", "b c");
        $this->_parseTest("a=\"b'c\"", "a", "b'c");
        $this->_parseTest("a='b\"c'", "a", "b\"c");
        $this->_parseTest("a='b=c'", "a", "b=c");
        $this->_parseTest("a=\"b=c\"", "a", "b=c");
        $this->_parseTest("a='b;c=d'", "a", "b;c=d");
        $this->_parseTest("a=\"b;c=d\"", "a", "b;c=d");
        $this->_parseTest("a='b c' ", "a", "b c");
        $this->_parseTest("a=\"b c\" ", "a", "b c");
        $this->_parseTest("a=b'c", "a", "b'c");
        $this->_parseTest("a=b\"c", "a", "b\"c");
        $this->_parseTest("a=b'", "a", "b'");
        $this->_parseTest("a=b\"", "a", "b\"");
    }

    public function testSeparators(): void
    {
        $this->_parseTest("a=b;", "a", "b");
        $this->_parseTest("a=b", "a", "b");
        $this->_parseTest("a=b;c=d", "a", "b", "c", "d");
        $this->_parseTest("a=b;c=d;", "a", "b", "c", "d");
        $this->_parseTest("a=b ; c=d", "a", "b", "c", "d");
    }

    public function testInvalidInputFail(): void
    {
        $this->_parseTestFail(";");           // Separator without an assignment;
        $this->_parseTestFail("=b");          // Missing key name;
        $this->_parseTestFail("''=b");        // Empty key name;
        $this->_parseTestFail("\"\"=b");      // Empty key name;
        $this->_parseTestFail("test");        // Missing assignment;
        $this->_parseTestFail(";a=b");        // Separator without key=value;
        $this->_parseTestFail("a=b;;");       // Two separators at the end;
        $this->_parseTestFail("a=b;;c=d");    // Two separators in the middle.
        $this->_parseTestFail("'a=b");        // Runaway single-quoted string at the beginning of the key name;
        $this->_parseTestFail("\"a=b");       // Runaway double-quoted string at the beginning of the key name;
        $this->_parseTestFail("'=b");         // Runaway single-quoted string in key name;
        $this->_parseTestFail("\"=b");        // Runaway double-quoted string in key name;
        $this->_parseTestFail("a='b");        // Runaway single-quoted string in value;
        $this->_parseTestFail("a=\"b");       // Runaway double-quoted string in value;
        $this->_parseTestFail("a='b'c");      // Extra character after single-quoted value;
        $this->_parseTestFail("a=\"b\"c");    // Extra character after double-quoted value;
        $this->_parseTestFail("'a'b=c");      // Extra character after single-quoted key;
        $this->_parseTestFail("\"a\"b=c");    // Extra character after double-quoted key;
    }

}
