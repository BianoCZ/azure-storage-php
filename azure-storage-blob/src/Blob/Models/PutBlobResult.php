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
 * @package   MicrosoftAzure\Storage\Blob\Models
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2017 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */

namespace MicrosoftAzure\Storage\Blob\Models;

use DateTime;
use MicrosoftAzure\Storage\Blob\Internal\BlobResources as Resources;
use MicrosoftAzure\Storage\Common\Internal\Utilities;

/**
 * The result of calling PutBlob API.
 *
 * @category  Microsoft
 * @package   MicrosoftAzure\Storage\Blob\Models
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2017 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */
class PutBlobResult
{

    private $contentMD5;

    private $etag;

    private $lastModified;

    private $requestServerEncrypted;

    /**
     * Creates PutBlobResult object from the response of the put blob request.
     *
     * @param array $headers The HTTP response headers in array representation.
     *
     * @internal
     *
     */
    public static function create(array $headers): PutBlobResult
    {
        $result = new PutBlobResult();

        $result->setETag(
            Utilities::tryGetValueInsensitive(
                Resources::ETAG,
                $headers
            )
        );

        if (
            Utilities::arrayKeyExistsInsensitive(
                Resources::LAST_MODIFIED,
                $headers
            )
        ) {
            $lastModified = Utilities::tryGetValueInsensitive(
                Resources::LAST_MODIFIED,
                $headers
            );
            $result->setLastModified(Utilities::rfc1123ToDateTime($lastModified));
        }

        $result->setContentMD5(
            Utilities::tryGetValueInsensitive(Resources::CONTENT_MD5, $headers)
        );

        $result->setRequestServerEncrypted(
            Utilities::toBoolean(
                Utilities::tryGetValueInsensitive(
                    Resources::X_MS_REQUEST_SERVER_ENCRYPTED,
                    $headers
                ),
                true
            )
        );

        return $result;
    }

    /**
     * Gets ETag.
     *
     */
    public function getETag(): string
    {
        return $this->etag;
    }

    /**
     * Sets ETag.
     *
     * @param string $etag value.
     *
     */
    protected function setETag(string $etag): void
    {
        $this->etag = $etag;
    }

    /**
     * Gets blob lastModified.
     *
     */
    public function getLastModified(): DateTime
    {
        return $this->lastModified;
    }

    /**
     * Sets blob lastModified.
     *
     * @param \DateTime $lastModified value.
     *
     */
    protected function setLastModified(DateTime $lastModified): void
    {
        $this->lastModified = $lastModified;
    }

    /**
     * Gets block content MD5.
     *
     */
    public function getContentMD5(): string
    {
        return $this->contentMD5;
    }

    /**
     * Sets the content MD5 value.
     *
     * @param string $contentMD5 conent MD5 as a string.
     *
     */
    protected function setContentMD5(string $contentMD5): void
    {
        $this->contentMD5 = $contentMD5;
    }

    /**
     * Gets the whether the contents of the request are successfully encrypted.
     *
     */
    public function getRequestServerEncrypted(): bool
    {
        return $this->requestServerEncrypted;
    }

    /**
     * Sets the request server encryption value.
     *
     *
     */
    public function setRequestServerEncrypted(bool $requestServerEncrypted): void
    {
        $this->requestServerEncrypted = $requestServerEncrypted;
    }

}
