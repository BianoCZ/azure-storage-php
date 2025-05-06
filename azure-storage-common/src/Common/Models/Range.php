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

/**
 * Holds info about resource+ range used in HTTP requests
 *
 * @category  Microsoft
 * @package   MicrosoftAzure\Storage\Common\Models
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */
class Range
{

    private $start;

    private $end;

    /**
     * Constructor
     *
     * @param int $start the resource start value
     * @param int $end   the resource end value
     */
    public function __construct(int $start, ?int $end = null)
    {
        $this->start = $start;
        $this->end   = $end;
    }

    /**
     * Sets resource start range
     *
     * @param int $start the resource range start
     *
     */
    public function setStart(int $start): void
    {
        $this->start = $start;
    }

    /**
     * Gets resource start range
     *
     */
    public function getStart(): int
    {
        return $this->start;
    }

    /**
     * Sets resource end range
     *
     * @param int $end the resource range end
     *
     */
    public function setEnd(int $end): void
    {
        $this->end = $end;
    }

    /**
     * Gets resource end range
     *
     */
    public function getEnd(): int
    {
        return $this->end;
    }

    /**
     * Gets resource range length
     *
     */
    public function getLength(): int
    {
        if ($this->end != null) {
            return $this->end - $this->start + 1;
        }

        return null;
    }

    /**
     * Sets resource range length
     *
     * @param int $value new resource range
     *
     */
    public function setLength(int $value): void
    {
        $this->end = $this->start + $value - 1;
    }

    /**
     * Constructs the range string according to the set start and end
     *
     */
    public function getRangeString(): string
    {
        $rangeString = '';

        $rangeString .= ('bytes=' . $this->start . '-');
        if ($this->end != null) {
            $rangeString .= $this->end;
        }

        return $rangeString;
    }

}
