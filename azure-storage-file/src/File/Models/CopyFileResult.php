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
 * @copyright 2017 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */

namespace MicrosoftAzure\Storage\File\Models;

use DateTime;
use MicrosoftAzure\Storage\Common\Internal\Utilities;
use MicrosoftAzure\Storage\Common\Internal\Validate;
use MicrosoftAzure\Storage\File\Internal\FileResources as Resources;
use function array_change_key_case;

/**
 * Holds result of calling CopyFileResult wrapper
 *
 * @category  Microsoft
 * @package   MicrosoftAzure\Storage\File\Models
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2017 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */
class CopyFileResult
{

    private $lastModified;

    private $etag;

    private $copyID;

    private $copyStatus;

    /**
     * Creates CopyFileResult object from parsed response header.
     *
     * @param array $headers HTTP response headers
     *
     * @internal
     *
     */
    public static function create(array $headers): CopyFileResult
    {
        $result  = new CopyFileResult();
        $headers = array_change_key_case($headers);

        $date          = $headers[Resources::LAST_MODIFIED];
        $date          = Utilities::rfc1123ToDateTime($date);

        $result->setCopyStatus($headers[Resources::X_MS_COPY_STATUS]);
        $result->setCopyID($headers[Resources::X_MS_COPY_ID]);
        $result->setETag($headers[Resources::ETAG]);
        $result->setLastModified($date);

        return $result;
    }

    /**
     * Gets file lastModified.
     *
     */
    public function getLastModified(): DateTime
    {
        return $this->lastModified;
    }

    /**
     * Sets file lastModified.
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
     * Gets file etag.
     *
     */
    public function getETag(): string
    {
        return $this->etag;
    }

    /**
     * Sets file etag.
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
     * Gets file copyID.
     *
     */
    public function getCopyID(): string
    {
        return $this->copyID;
    }

    /**
     * Sets file copyID.
     *
     * @param string $copyID value.
     *
     */
    protected function setCopyID(string $copyID): void
    {
        Validate::canCastAsString($copyID, 'copyID');
        $this->copyID = $copyID;
    }

    /**
     * Gets copyStatus
     *
     */
    public function getCopyStatus(): string
    {
        return $this->copyStatus;
    }

    /**
     * Sets copyStatus
     *
     * @param string $copyStatus copyStatus to set
     *
     */
    protected function setCopyStatus(string $copyStatus): void
    {
        Validate::canCastAsString($copyStatus, 'copyStatus');
        $this->copyStatus = $copyStatus;
    }

}
