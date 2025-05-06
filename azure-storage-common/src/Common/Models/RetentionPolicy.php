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
 * @package   MicrosoftAzure\Storage\Common\Models
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */

namespace MicrosoftAzure\Storage\Common\Models;

use MicrosoftAzure\Storage\Common\Internal\Utilities;
use function intval;
use function strval;

/**
 * Holds elements of queue properties retention policy field.
 *
 * @category  Microsoft
 * @package   MicrosoftAzure\Storage\Common\Models
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */
class RetentionPolicy
{

    private $_enabled;

    private $_days;

    /**
     * Creates object from $parsedResponse.
     *
     * @param array $parsedResponse XML response parsed into array.
     *
     * @internal
     *
     */
    public static function create(?array $parsedResponse = null): MicrosoftAzure\Storage\Common\Models\RetentionPolicy
    {
        $result = new RetentionPolicy();
        $result->setEnabled(Utilities::toBoolean($parsedResponse['Enabled']));
        if ($result->getEnabled()) {
            $result->setDays(intval($parsedResponse['Days']));
        }

        return $result;
    }

    /**
     * Gets enabled.
     *
     */
    public function getEnabled(): bool
    {
        return $this->_enabled;
    }

    /**
     * Sets enabled.
     *
     * @param bool $enabled value to use.
     *
     */
    public function setEnabled(bool $enabled): void
    {
        $this->_enabled = $enabled;
    }

    /**
     * Gets days field.
     *
     */
    public function getDays(): int
    {
        return $this->_days;
    }

    /**
     * Sets days field.
     *
     * @param int $days value to use.
     *
     */
    public function setDays(int $days): void
    {
        $this->_days = $days;
    }

    /**
     * Converts this object to array with XML tags
     *
     * @internal
     *
     */
    public function toArray(): array
    {
        $array = ['Enabled' => Utilities::booleanToString($this->_enabled)];
        if (isset($this->_days)) {
            $array['Days'] = strval($this->_days);
        }

        return $array;
    }

}
