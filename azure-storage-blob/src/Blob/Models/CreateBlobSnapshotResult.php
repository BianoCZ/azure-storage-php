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
use function array_change_key_case;

/**
 * The result of creating Blob snapshot.
 *
 * @category  Microsoft
 * @package   MicrosoftAzure\Storage\Blob\Models
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */
class CreateBlobSnapshotResult
{

    private $_snapshot;

    private $_etag;

    private $_lastModified;

    /**
     * Creates CreateBlobSnapshotResult object from the response of the
     * create Blob snapshot request.
     *
     * @param array $headers The HTTP response headers in array representation.
     *
     * @internal
     *
     */
    public static function create(array $headers): CreateBlobSnapshotResult
    {
        $result                 = new CreateBlobSnapshotResult();
        $headerWithLowerCaseKey = array_change_key_case($headers);

        $result->setETag($headerWithLowerCaseKey[Resources::ETAG]);

        $result->setLastModified(
            Utilities::rfc1123ToDateTime(
                $headerWithLowerCaseKey[Resources::LAST_MODIFIED]
            )
        );

        $result->setSnapshot($headerWithLowerCaseKey[Resources::X_MS_SNAPSHOT]);

        return $result;
    }

    /**
     * Gets snapshot.
     *
     */
    public function getSnapshot(): string
    {
        return $this->_snapshot;
    }

    /**
     * Sets snapshot.
     *
     * @param string $snapshot value.
     *
     */
    protected function setSnapshot(string $snapshot): void
    {
        $this->_snapshot = $snapshot;
    }

    /**
     * Gets ETag.
     *
     */
    public function getETag(): string
    {
        return $this->_etag;
    }

    /**
     * Sets ETag.
     *
     * @param string $etag value.
     *
     */
    protected function setETag(string $etag): void
    {
        $this->_etag = $etag;
    }

    /**
     * Gets blob lastModified.
     *
     */
    public function getLastModified(): DateTime
    {
        return $this->_lastModified;
    }

    /**
     * Sets blob lastModified.
     *
     * @param \DateTime $lastModified value.
     *
     */
    protected function setLastModified(DateTime $lastModified): void
    {
        $this->_lastModified = $lastModified;
    }

}
