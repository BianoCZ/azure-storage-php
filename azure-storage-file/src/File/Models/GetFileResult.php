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
 * @package   MicrosoftAzure\Storage\File\Models
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */

namespace MicrosoftAzure\Storage\File\Models;

use Psr\Http\Message\StreamInterface;
use function is_null;

/**
 * Holds result of GetFile API.
 *
 * @category  Microsoft
 * @package   MicrosoftAzure\Storage\File\Models
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */
class GetFileResult
{

    private $properties;

    private $metadata;

    private $contentStream;

    /**
     * Creates GetFileResult from getFile call.
     *
     * @param array           $headers  The HTTP response headers.
     * @param StreamInterface $body     The response body.
     * @param array           $metadata The file metadata.
     *
     * @internal
     *
     */
    public static function create(
        array $headers,
        StreamInterface $body,
        array $metadata
    ): GetFileResult {
        $result = new GetFileResult();
        $result->setContentStream($body->detach());
        $result->setProperties(FileProperties::createFromHttpHeaders($headers));
        $result->setMetadata(is_null($metadata) ? [] : $metadata);

        return $result;
    }

    /**
     * Gets file metadata.
     *
     */
    public function getMetadata(): array
    {
        return $this->metadata;
    }

    /**
     * Sets file metadata.
     *
     * @param array $metadata value.
     *
     */
    protected function setMetadata(array $metadata): void
    {
        $this->metadata = $metadata;
    }

    /**
     * Gets file properties.
     *
     */
    public function getProperties(): FileProperties
    {
        return $this->properties;
    }

    /**
     * Sets file properties.
     *
     * @param FileProperties $properties value.
     *
     */
    protected function setProperties(FileProperties $properties): void
    {
        $this->properties = $properties;
    }

    /**
     * Gets file contentStream.
     *
     * @return resource
     */
    public function getContentStream()
    {
        return $this->contentStream;
    }

    /**
     * Sets file contentStream.
     *
     * @param resource $contentStream The stream handle.
     *
     */
    protected function setContentStream($contentStream): void
    {
        $this->contentStream = $contentStream;
    }

}
