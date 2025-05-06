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
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */

namespace MicrosoftAzure\Storage\Blob\Models;

use DateTime;
use MicrosoftAzure\Storage\Blob\Internal\BlobResources as Resources;
use MicrosoftAzure\Storage\Common\Internal\Utilities;
use MicrosoftAzure\Storage\Common\Internal\Validate;

/**
 * Holds results of calling getBlobMetadata wrapper
 *
 * @category  Microsoft
 * @package   MicrosoftAzure\Storage\Blob\Models
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */
class SetBlobMetadataResult
{

    private $etag;

    private $lastModified;

    private $requestServerEncrypted;

    /**
     * Creates SetBlobMetadataResult from response headers.
     *
     * @param array $headers response headers
     *
     * @internal
     *
     */
    public static function create(array $headers): SetBlobMetadataResult
    {
        $result = new SetBlobMetadataResult();

        $result->setETag(Utilities::tryGetValueInsensitive(
            Resources::ETAG,
            $headers
        ));

        $date = Utilities::tryGetValueInsensitive(
            Resources::LAST_MODIFIED,
            $headers
        );
        $result->setLastModified(Utilities::rfc1123ToDateTime($date));

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
        Validate::isDate($lastModified);
        $this->lastModified = $lastModified;
    }

    /**
     * Gets blob etag.
     *
     */
    public function getETag(): string
    {
        return $this->etag;
    }

    /**
     * Sets blob etag.
     *
     * @param string $etag value.
     *
     */
    protected function setETag(string $etag): void
    {
        Validate::canCastAsString($etag, 'etag');
        $this->etag = $etag;
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
