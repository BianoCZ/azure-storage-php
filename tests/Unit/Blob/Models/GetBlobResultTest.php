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

use GuzzleHttp\Psr7;
use MicrosoftAzure\Storage\Blob\Models\BlobProperties;
use MicrosoftAzure\Storage\Blob\Models\GetBlobResult;
use MicrosoftAzure\Storage\Tests\Framework\TestResources;
use PHPUnit\Framework\TestCase;
use function stream_get_contents;

/**
 * Unit tests for class GetBlobResult
 *
 * @category  Microsoft
 * @package   MicrosoftAzure\Storage\Tests\Unit\Blob\Models
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */
class GetBlobResultTest extends TestCase
{

    public function testCreate(): void
    {
        // Setup
        $sample = TestResources::listBlobsOneEntry();
        $expected = $sample['Blobs']['Blob']['Properties'];
        $expectedProperties = BlobProperties::createFromHttpHeaders($expected);
        $expectedMetadata = $sample['Blobs']['Blob']['Metadata'];
        $expectedBody = 'test data';

        // Test
        $actual = GetBlobResult::create(
            $expected,
            Psr7\Utils::streamFor($expectedBody),
            $expectedMetadata
        );

        // Assert
        $this->assertEquals($expectedProperties, $actual->getProperties());
        $this->assertEquals($expectedMetadata, $actual->getMetadata());
        $actualContent = stream_get_contents($actual->getContentStream());
        $this->assertEquals($expectedBody, $actualContent);
    }

}
