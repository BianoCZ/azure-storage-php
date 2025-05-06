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
 * @package   MicrosoftAzure\Storage\Table\Models\Filters
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */

namespace MicrosoftAzure\Storage\Table\Models\Filters;

/**
 * Filter operations
 *
 * @category  Microsoft
 * @package   MicrosoftAzure\Storage\Table\Models\Filters
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */
class Filter
{
    /**
     * Apply and operation between two filters
     *
     * @param Filter $left  The left filter
     * @param Filter $right The right filter
     *
     */
    public static function applyAnd(Filter $left, Filter $right): BinaryFilter
    {
        return new BinaryFilter($left, 'and', $right);
    }

    /**
     * Applies not operation on $operand
     *
     * @param Filter $operand The operand
     *
     */
    public static function applyNot(Filter $operand): UnaryFilter
    {
        return new UnaryFilter('not', $operand);
    }

    /**
     * Apply or operation on the passed filers
     *
     * @param Filter $left  The left operand
     * @param Filter $right The right operand
     *
     */
    public static function applyOr(Filter $left, Filter $right): BinaryFilter
    {
        return new BinaryFilter($left, 'or', $right);
    }

    /**
     * Apply eq operation on the passed filers
     *
     * @param Filter $left  The left operand
     * @param Filter $right The right operand
     *
     */
    public static function applyEq(Filter $left, Filter $right): BinaryFilter
    {
        return new BinaryFilter($left, 'eq', $right);
    }

    /**
     * Apply ne operation on the passed filers
     *
     * @param Filter $left  The left operand
     * @param Filter $right The right operand
     *
     */
    public static function applyNe(Filter $left, Filter $right): BinaryFilter
    {
        return new BinaryFilter($left, 'ne', $right);
    }

    /**
     * Apply ge operation on the passed filers
     *
     * @param Filter $left  The left operand
     * @param Filter $right The right operand
     *
     */
    public static function applyGe(Filter $left, Filter $right): BinaryFilter
    {
        return new BinaryFilter($left, 'ge', $right);
    }

    /**
     * Apply gt operation on the passed filers
     *
     * @param Filter $left  The left operand
     * @param Filter $right The right operand
     *
     */
    public static function applyGt(Filter $left, Filter $right): BinaryFilter
    {
        return new BinaryFilter($left, 'gt', $right);
    }

    /**
     * Apply lt operation on the passed filers
     *
     * @param Filter $left  The left operand
     * @param Filter $right The right operand
     *
     */
    public static function applyLt(Filter $left, Filter $right): BinaryFilter
    {
        return new BinaryFilter($left, 'lt', $right);
    }

    /**
     * Apply le operation on the passed filers
     *
     * @param Filter $left  The left operand
     * @param Filter $right The right operand
     *
     */
    public static function applyLe(Filter $left, Filter $right): BinaryFilter
    {
        return new BinaryFilter($left, 'le', $right);
    }

    /**
     * Apply constant filter on value.
     *
     * @param mixed  $value   The filter value
     * @param string $edmType The value EDM type.
     *
     */
    public static function applyConstant(mixed $value, ?string $edmType = null): ConstantFilter
    {
        return new ConstantFilter($edmType, $value);
    }

    /**
     * Apply propertyName filter on $value
     *
     * @param string $value The filter value
     *
     */
    public static function applyPropertyName(string $value): PropertyNameFilter
    {
        return new PropertyNameFilter($value);
    }

    /**
     * Takes raw string filter
     *
     * @param string $value The raw string filter expression
     *
     */
    public static function applyQueryString(string $value): QueryStringFilter
    {
        return new QueryStringFilter($value);
    }

}
