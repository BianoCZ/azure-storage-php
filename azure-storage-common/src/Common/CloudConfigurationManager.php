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
 * @package   MicrosoftAzure\Storage\Common
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */

namespace MicrosoftAzure\Storage\Common;

use MicrosoftAzure\Storage\Common\Internal\ConnectionStringSource;
use MicrosoftAzure\Storage\Common\Internal\Utilities;
use MicrosoftAzure\Storage\Common\Internal\Validate;
use function array_merge;
use function call_user_func_array;
use function is_null;

/**
 * Configuration manager for accessing Windows Azure settings.
 *
 * @category  Microsoft
 * @package   MicrosoftAzure\Storage\Common
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */
class CloudConfigurationManager
{

    private static $_isInitialized = false;

    private static $_sources;

    /**
     * Restrict users from creating instances from this class
     */
    private function __construct()
    {
    }

    /**
     * Initializes the connection string source providers.
     *
     */
    private static function _init(): void
    {
        if (!self::$_isInitialized) {
            self::$_sources = [];

            // Get list of default connection string sources.
            $default = ConnectionStringSource::getDefaultSources();
            foreach ($default as $name => $provider) {
                self::$_sources[$name] = $provider;
            }

            self::$_isInitialized = true;
        }
    }

    /**
     * Gets a connection string from all available sources.
     *
     * @param string $key The connection string key name.
     *
     * @return string If the key does not exist return null.
     */
    public static function getConnectionString(string $key): string
    {
        Validate::canCastAsString($key, 'key');

        self::_init();
        $value = null;

        foreach (self::$_sources as $source) {
            $value = call_user_func_array($source, [$key]);

            if (!empty($value)) {
                break;
            }
        }

        return $value;
    }

    /**
     * Registers a new connection string source provider. If the source to get
     * registered is a default source, only the name of the source is required.
     *
     * @param string   $name     The source name.
     * @param callable $provider The source callback.
     * @param bool  $prepend  When true, the $provider is processed first when
     * calling getConnectionString. When false (the default) the $provider is
     * processed after the existing callbacks.
     *
     */
    public static function registerSource(string $name, ?callable $provider = null, bool $prepend = false): void
    {
        Validate::canCastAsString($name, 'name');
        Validate::notNullOrEmpty($name, 'name');

        self::_init();
        $default = ConnectionStringSource::getDefaultSources();

        // Try to get callback if the user is trying to register a default source.
        $provider = Utilities::tryGetValue($default, $name, $provider);

        Validate::notNullOrEmpty($provider, 'callback');

        if ($prepend) {
            self::$_sources = array_merge(
                [$name => $provider],
                self::$_sources
            );
        } else {
            self::$_sources[$name] = $provider;
        }
    }

    /**
     * Unregisters a connection string source.
     *
     * @param string $name The source name.
     *
     */
    public static function unregisterSource(string $name): callable
    {
        Validate::canCastAsString($name, 'name');
        Validate::notNullOrEmpty($name, 'name');

        self::_init();

        $sourceCallback = Utilities::tryGetValue(self::$_sources, $name);

        if (!is_null($sourceCallback)) {
            unset(self::$_sources[$name]);
        }

        return $sourceCallback;
    }

}
