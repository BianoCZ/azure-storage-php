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
 * @package   MicrosoftAzure\Storage\Queue\Models
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */

namespace MicrosoftAzure\Storage\Queue\Models;

use MicrosoftAzure\Storage\Common\Internal\Utilities;
use MicrosoftAzure\Storage\Common\MarkerContinuationTokenTrait;
use MicrosoftAzure\Storage\Common\Models\MarkerContinuationToken;
use MicrosoftAzure\Storage\Queue\Internal\QueueResources as Resources;
use function is_null;

/**
 * Container to hold list queue response object.
 *
 * @category  Microsoft
 * @package   MicrosoftAzure\Storage\Queue\Models
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */
class ListQueuesResult
{

    use MarkerContinuationTokenTrait;

    private $_queues;

    private $_prefix;

    private $_marker;

    private $_maxResults;

    private $_accountName;

    /**
     * Creates ListQueuesResult object from parsed XML response.
     *
     * @param array  $parsedResponse XML response parsed into array.
     * @param string $location       Contains the location for the previous
     *                               request.
     *
     * @internal
     *
     */
    public static function create(array $parsedResponse, string $location = ''): ListQueuesResult
    {
        $result               = new ListQueuesResult();
        $serviceEndpoint      = Utilities::tryGetKeysChainValue(
            $parsedResponse,
            Resources::XTAG_ATTRIBUTES,
            Resources::XTAG_SERVICE_ENDPOINT
        );
        $result->setAccountName(Utilities::tryParseAccountNameFromUrl(
            $serviceEndpoint
        ));
        $result->setPrefix(Utilities::tryGetValue(
            $parsedResponse,
            Resources::QP_PREFIX
        ));
        $result->setMarker(Utilities::tryGetValue(
            $parsedResponse,
            Resources::QP_MARKER
        ));

        $nextMarker = Utilities::tryGetValue($parsedResponse, Resources::QP_NEXT_MARKER);

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
        $queues      = [];
        $rawQueues            = [];

        if (!empty($parsedResponse['Queues'])) {
            $rawQueues = Utilities::getArray($parsedResponse['Queues']['Queue']);
        }

        foreach ($rawQueues as $value) {
            $queue    = new Queue($value['Name'], $serviceEndpoint . $value['Name']);
            $metadata = Utilities::tryGetValue($value, Resources::QP_METADATA);
            $queue->setMetadata(is_null($metadata) ? [] : $metadata);
            $queues[] = $queue;
        }
        $result->setQueues($queues);
        return $result;
    }

    /**
     * Gets queues.
     *
     */
    public function getQueues(): array
    {
        return $this->_queues;
    }

    /**
     * Sets queues.
     *
     * @param array $queues list of queues
     *
     * @internal
     *
     */
    protected function setQueues(array $queues): void
    {
        $this->_queues = [];
        foreach ($queues as $queue) {
            $this->_queues[] = clone $queue;
        }
    }

    /**
     * Gets prefix.
     *
     */
    public function getPrefix(): string
    {
        return $this->_prefix;
    }

    /**
     * Sets prefix.
     *
     * @param string $prefix value.
     *
     * @internal
     *
     */
    protected function setPrefix(string $prefix): void
    {
        $this->_prefix = $prefix;
    }

    /**
     * Gets marker.
     *
     */
    public function getMarker(): string
    {
        return $this->_marker;
    }

    /**
     * Sets marker.
     *
     * @param string $marker value.
     *
     * @internal
     *
     */
    protected function setMarker(string $marker): void
    {
        $this->_marker = $marker;
    }

    /**
     * Gets max results.
     *
     */
    public function getMaxResults(): string
    {
        return $this->_maxResults;
    }

    /**
     * Sets max results.
     *
     * @param string $maxResults value.
     *
     * @internal
     *
     */
    protected function setMaxResults(string $maxResults): void
    {
        $this->_maxResults = $maxResults;
    }

    /**
     * Gets account name.
     *
     */
    public function getAccountName(): string
    {
        return $this->_accountName;
    }

    /**
     * Sets account name.
     *
     * @param string $accountName value.
     *
     * @internal
     *
     */
    protected function setAccountName(string $accountName): void
    {
        $this->_accountName = $accountName;
    }

}
