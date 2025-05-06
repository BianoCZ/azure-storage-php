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

use MicrosoftAzure\Storage\Common\Internal\Utilities;
use MicrosoftAzure\Storage\Common\MarkerContinuationTokenTrait;
use MicrosoftAzure\Storage\Common\Models\MarkerContinuationToken;
use MicrosoftAzure\Storage\File\Internal\FileResources as Resources;
use function array_key_exists;

/**
 * Share to hold list directories and files response object.
 *
 * @category  Microsoft
 * @package   MicrosoftAzure\Storage\File\Models
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */
class ListDirectoriesAndFilesResult
{

    use MarkerContinuationTokenTrait;

    private $directories;

    private $files;

    private $maxResults;

    private $accountName;

    private $marker;

    /**
     * Creates ListDirectoriesAndFilesResult object from parsed XML response.
     *
     * @param array  $parsedResponse XML response parsed into array.
     * @param string $location       Contains the location for the previous
     *                               request.
     *
     * @internal
     *
     */
    public static function create(array $parsedResponse, string $location = ''): ListDirectoriesAndFilesResult
    {
        $result               = new ListDirectoriesAndFilesResult();
        $serviceEndpoint      = Utilities::tryGetKeysChainValue(
            $parsedResponse,
            Resources::XTAG_ATTRIBUTES,
            Resources::XTAG_SERVICE_ENDPOINT
        );
        $result->setAccountName(Utilities::tryParseAccountNameFromUrl(
            $serviceEndpoint
        ));

        $nextMarker = Utilities::tryGetValue(
            $parsedResponse,
            Resources::QP_NEXT_MARKER
        );

        if ($nextMarker != null) {
            $result->setContinuationToken(
                new MarkerContinuationToken(
                    $nextMarker,
                    $location
                )
            );
        }

        $result->setMaxResults(Utilities::tryGetValue(
            $parsedResponse,
            Resources::QP_MAX_RESULTS
        ));

        $result->setMarker(Utilities::tryGetValue(
            $parsedResponse,
            Resources::QP_MARKER
        ));

        $entries = Utilities::tryGetValue(
            $parsedResponse,
            Resources::QP_ENTRIES
        );

        if (empty($entries)) {
            $result->setDirectories([]);
            $result->setFiles([]);
        } else {
            $directoriesArray = Utilities::tryGetValue(
                $entries,
                Resources::QP_DIRECTORY
            );
            $filesArray = Utilities::tryGetValue(
                $entries,
                Resources::QP_FILE
            );

            $directories = [];
            $files = [];

            if ($directoriesArray != null) {
                if (array_key_exists(Resources::QP_NAME, $directoriesArray)) {
                    $directoriesArray = [$directoriesArray];
                }
                foreach ($directoriesArray as $directoryArray) {
                    $directories[] = Directory::create($directoryArray);
                }
            }

            if ($filesArray != null) {
                if (array_key_exists(Resources::QP_NAME, $filesArray)) {
                    $filesArray = [$filesArray];
                }
                foreach ($filesArray as $fileArray) {
                    $files[] = File::create($fileArray);
                }
            }

            $result->setDirectories($directories);
            $result->setFiles($files);
        }

        return $result;
    }

    /**
     * Sets Directories.
     *
     * @param array $directories list of directories.
     *
     */
    protected function setDirectories(array $directories): void
    {
        $this->directories = [];
        foreach ($directories as $directory) {
            $this->directories[] = clone $directory;
        }
    }

    /**
     * Gets directories.
     *
     * @return Directory[]
     */
    public function getDirectories(): array
    {
        return $this->directories;
    }

    /**
     * Sets files.
     *
     * @param array $files list of files.
     *
     */
    protected function setFiles(array $files): void
    {
        $this->files = [];
        foreach ($files as $file) {
            $this->files[] = clone $file;
        }
    }

    /**
     * Gets files.
     *
     * @return File[]
     */
    public function getFiles(): array
    {
        return $this->files;
    }

    /**
     * Gets max results.
     *
     */
    public function getMaxResults(): string
    {
        return $this->maxResults;
    }

    /**
     * Sets max results.
     *
     * @param string $maxResults value.
     *
     */
    protected function setMaxResults(string $maxResults): void
    {
        $this->maxResults = $maxResults;
    }

    /**
     * Gets marker.
     *
     */
    public function getMarker(): string
    {
        return $this->marker;
    }

    /**
     * Sets marker.
     *
     * @param string $marker value.
     *
     */
    protected function setMarker(string $marker): void
    {
        $this->marker = $marker;
    }

    /**
     * Gets account name.
     *
     */
    public function getAccountName(): string
    {
        return $this->accountName;
    }

    /**
     * Sets account name.
     *
     * @param string $accountName value.
     *
     */
    protected function setAccountName(string $accountName): void
    {
        $this->accountName = $accountName;
    }

}
