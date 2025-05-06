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

use MicrosoftAzure\Storage\Blob\Models\AccessCondition;
use MicrosoftAzure\Storage\Blob\Models\CopyBlobOptions;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for class CopyBlobBlobOptions
 *
 * @category  Microsoft
 * @package   MicrosoftAzure\Storage\Tests\Unit\Blob\Models
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */
class CopyBlobOptionsTest extends TestCase
{
    public function testSetMetadata(): void
    {
        $copyBlobOptions = new CopyBlobOptions();
        $expected = ['key1' => 'value1', 'key2' => 'value2'];
        $copyBlobOptions->setMetadata($expected);

        $this->assertEquals(
            $expected,
            $copyBlobOptions->getMetadata()
        );
    }

    public function testSetAccessConditions(): void
    {
        $copyBlobOptions = new CopyBlobOptions();
        $expected = AccessCondition::ifMatch("12345");
        $copyBlobOptions->setAccessConditions($expected);

        $this->assertEquals(
            $expected,
            $copyBlobOptions->getAccessConditions()[0]
        );
    }

    public function testSetSourceAccessConditions(): void
    {
        $copyBlobOptions = new CopyBlobOptions();
        $expected = AccessCondition::IfMatch("x");
        $copyBlobOptions->setSourceAccessConditions($expected);

        $this->assertEquals(
            $expected,
            $copyBlobOptions->getSourceAccessConditions()[0]
        );
    }

    public function testSetLeaseId(): void
    {
        $expected = '0x8CAFB82EFF70C46';
        $options = new CopyBlobOptions();

        $options->setLeaseId($expected);
        $this->assertEquals($expected, $options->getLeaseId());
    }

    public function testSetSourceLeaseId(): void
    {
        $expected = '0x8CAFB82EFF70C46';
        $options = new CopyBlobOptions();

        $options->setSourceLeaseId($expected);
        $this->assertEquals($expected, $options->getSourceLeaseId());
    }

    public function testSetIsIncrementalCopy(): void
    {
        $expected = true;
        $options = new CopyBlobOptions();

        $options->setIsIncrementalCopy($expected);
        $this->assertEquals($expected, $options->getIsIncrementalCopy());
    }

    public function testSetSourceSnapshot(): void
    {
        $expected = '2017-09-19T10:39:36.8401215Z';
        $options = new CopyBlobOptions();

        $options->setSourceSnapshot($expected);
        $this->assertEquals($expected, $options->getSourceSnapshot());
    }

}
