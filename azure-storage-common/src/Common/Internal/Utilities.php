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
 * @ignore
 * @category  Microsoft
 * @package   MicrosoftAzure\Storage\Common\Internal
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */

namespace MicrosoftAzure\Storage\Common\Internal;

use DateTime;
use DateTimeInterface;
use DateTimeZone;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use RuntimeException;
use SimpleXMLElement;
use XmlWriter;
use function array_change_key_case;
use function array_key_exists;
use function array_map;
use function base64_encode;
use function bcadd;
use function bcmul;
use function count;
use function explode;
use function fclose;
use function filter_var;
use function fopen;
use function func_get_args;
use function func_num_args;
use function fwrite;
use function gettype;
use function implode;
use function in_array;
use function is_array;
use function is_double;
use function is_int;
use function is_null;
use function is_numeric;
use function is_object;
use function is_string;
use function md5;
use function openssl_random_pseudo_bytes;
use function ord;
use function parse_url;
use function sprintf;
use function str_ireplace;
use function str_replace;
use function strcmp;
use function strlen;
use function strpos;
use function strtolower;
use function substr;
use function urlencode;
use const FILTER_VALIDATE_BOOLEAN;
use const PHP_INT_SIZE;
use const PHP_URL_HOST;
use const PHP_URL_SCHEME;

/**
 * Utilities for the project
 *
 * @category  Microsoft
 * @package   MicrosoftAzure\Storage\Common\Internal
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */
class Utilities
{
    /**
     * Returns the specified value of the $key passed from $array and in case that
     * this $key doesn't exist, the default value is returned.
     *
     * @param array $array   The array to be used.
     * @param mixed $key     The array key.
     * @param mixed $default The value to return if $key is not found in $array.
     *
     */
    public static function tryGetValue(array $array, mixed $key, mixed $default = null): mixed
    {
        return (!is_null($array)) && is_array($array) && array_key_exists($key, $array)
            ? $array[$key]
            : $default;
    }

    /**
     * Adds a url scheme if there is no scheme. Return null if input URL is null.
     *
     * @param string $url    The URL.
     * @param string $scheme The scheme. By default HTTP
     *
     */
    public static function tryAddUrlScheme(string $url, string $scheme = 'http'): string
    {
        if ($url == null) {
            return $url;
        }

        $urlScheme = parse_url($url, PHP_URL_SCHEME);

        if (empty($urlScheme)) {
            $url = "$scheme://" . $url;
        }

        return $url;
    }

    /**
     * Parse storage account name from an endpoint url.
     *
     * @param string $url The endpoint $url
     *
     */
    public static function tryParseAccountNameFromUrl(string $url): string
    {
        $host = parse_url($url, PHP_URL_HOST);

        // first token of the url host is account name
        return explode('.', $host)[0];
    }

    /**
     * Parse secondary endpoint url string from a primary endpoint url.
     *
     * Return null if the primary endpoint url is invalid.
     *
     * @param string $uri The primary endpoint url string.
     *
     */
    public static function tryGetSecondaryEndpointFromPrimaryEndpoint(string $uri): ?string
    {
        $splitTokens = explode('.', $uri);
        if (count($splitTokens) > 0 && $splitTokens[0] != '') {
            $schemaAccountToken = $splitTokens[0];
            $schemaAccountSplitTokens = explode('/', $schemaAccountToken);
            if (
                count($schemaAccountSplitTokens) > 0 &&
                $schemaAccountSplitTokens[0] != ''
            ) {
                $accountName = $schemaAccountSplitTokens[count($schemaAccountSplitTokens) - 1];
                $schemaAccountSplitTokens[count($schemaAccountSplitTokens) - 1] =
                    $accountName . Resources::SECONDARY_STRING;

                $splitTokens[0] = implode('/', $schemaAccountSplitTokens);
                return implode('.', $splitTokens);
            }
        }
        return null;
    }

    /**
     * tries to get nested array with index name $key from $array.
     *
     * Returns empty array object if the value is NULL.
     *
     * @param string $key   The index name.
     * @param array  $array The array object.
     *
     */
    public static function tryGetArray(string $key, array $array): array
    {
        return self::getArray(self::tryGetValue($array, $key));
    }

    /**
     * Adds the given key/value pair into array if the value doesn't satisfy empty().
     *
     * This function just validates that the given $array is actually array. If it's
     * NULL the function treats it as array.
     *
     * @param string $key    The key.
     * @param string $value  The value.
     * @param array  &$array The array. If NULL will be used as array.
     *
     */
    public static function addIfNotEmpty(string $key, string $value, array &$array): void
    {
        if (!is_null($array)) {
            Validate::isArray($array, 'array');
        }

        if (!empty($value)) {
            $array[$key] = $value;
        }
    }

    /**
     * Returns the specified value of the key chain passed from $array and in case
     * that key chain doesn't exist, null is returned.
     *
     * @param array $array Array to be used.
     *
     */
    public static function tryGetKeysChainValue(array $array): mixed
    {
        $arguments    = func_get_args();
        $numArguments = func_num_args();

        $currentArray = $array;
        for ($i = 1; $i < $numArguments; $i++) {
            if (is_array($currentArray)) {
                if (array_key_exists($arguments[$i], $currentArray)) {
                    $currentArray = $currentArray[$arguments[$i]];
                } else {
                    return null;
                }
            } else {
                return null;
            }
        }

        return $currentArray;
    }

    /**
     * Checks if the passed $string starts with $prefix
     *
     * @param string  $string     word to seaech in
     * @param string  $prefix     prefix to be matched
     * @param bool $ignoreCase true to ignore case during the comparison;
     * otherwise, false
     *
     */
    public static function startsWith(string $string, string $prefix, bool $ignoreCase = false): bool
    {
        if ($ignoreCase) {
            $string = strtolower($string);
            $prefix = strtolower($prefix);
        }
        return $prefix == substr($string, 0, strlen($prefix));
    }

    /**
     * Returns grouped items from passed $var
     *
     * @param array $var item to group
     *
     */
    public static function getArray(array $var): array
    {
        if (is_null($var) || empty($var)) {
            return [];
        }

        foreach ($var as $value) {
            if (
                (gettype($value) == 'object')
                && ($value::class == 'SimpleXMLElement')
            ) {
                return (array) $var;
            }

            if (!is_array($value)) {
                return [$var];
            }
        }

        return $var;
    }

    /**
     * Unserializes the passed $xml into array.
     *
     * @param string $xml XML to be parsed.
     *
     */
    public static function unserialize(string $xml): array
    {
        $sxml = new SimpleXMLElement($xml);

        return self::_sxml2arr($sxml);
    }

    /**
     * Converts a SimpleXML object to an Array recursively
     * ensuring all sub-elements are arrays as well.
     *
     * @param string $sxml SimpleXML object
     * @param array  $arr  Array into which to store results
     *
     */
    private static function _sxml2arr(string $sxml, ?array $arr = null): array
    {
        foreach ((array) $sxml as $key => $value) {
            if (is_object($value) || (is_array($value))) {
                $arr[$key] = self::_sxml2arr($value);
            } else {
                $arr[$key] = $value;
            }
        }

        return $arr;
    }

    /**
     * Serializes given array into xml. The array indices must be string to use
     * them as XML tags.
     *
     * @param array  $array      object to serialize represented in array.
     * @param string $rootName   name of the XML root element.
     * @param string $defaultTag default tag for non-tagged elements.
     * @param string $standalone adds 'standalone' header tag, values 'yes'/'no'
     *
     */
    public static function serialize(
        array $array,
        string $rootName,
        ?string $defaultTag = null,
        ?string $standalone = null
    ): string {
        $xmlVersion  = '1.0';
        $xmlEncoding = 'UTF-8';

        if (!is_array($array)) {
            return false;
        }

        $xmlw = new XmlWriter();
        $xmlw->openMemory();
        $xmlw->startDocument($xmlVersion, $xmlEncoding, $standalone);

        $xmlw->startElement($rootName);

        self::_arr2xml($xmlw, $array, $defaultTag);

        $xmlw->endElement();

        return $xmlw->outputMemory(true);
    }

    /**
     * Takes an array and produces XML based on it.
     *
     * @param XMLWriter $xmlw       XMLWriter object that was previously instanted
     * and is used for creating the XML.
     * @param array     $data       Array to be converted to XML
     * @param string    $defaultTag Default XML tag to be used if none specified.
     *
     */
    private static function _arr2xml(
        \XMLWriter $xmlw,
        array $data,
        ?string $defaultTag = null
    ): void {
        foreach ($data as $key => $value) {
            if (strcmp($key, '@attributes') == 0) {
                foreach ($value as $attributeName => $attributeValue) {
                    $xmlw->writeAttribute($attributeName, $attributeValue);
                }
            } elseif (is_array($value)) {
                if (!is_int($key)) {
                    if ($key != Resources::EMPTY_STRING) {
                        $xmlw->startElement($key);
                    } else {
                        $xmlw->startElement($defaultTag);
                    }
                }

                self::_arr2xml($xmlw, $value);

                if (!is_int($key)) {
                    $xmlw->endElement();
                }
                continue;
            } else {
                $xmlw->writeElement($key, $value);
            }
        }
    }

    /**
     * Converts string into boolean value.
     *
     * @param string $obj       boolean value in string format.
     * @param bool   $skipNull  If $skipNull is set, will return NULL directly
     *                          when $obj is NULL.
     *
     */
    public static function toBoolean(string $obj, bool $skipNull = false): bool
    {
        if ($skipNull && is_null($obj)) {
            return null;
        }

        return filter_var($obj, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Converts string into boolean value.
     *
     * @param bool $obj boolean value to convert.
     *
     */
    public static function booleanToString(bool $obj): string
    {
        return $obj ? 'true' : 'false';
    }

    /**
     * Converts a given date string into \DateTime object
     *
     * @param string $date windows azure date ins string representation.
     *
     */
    public static function rfc1123ToDateTime(string $date): DateTime
    {
        $timeZone = new DateTimeZone('GMT');
        $format   = Resources::AZURE_DATE_FORMAT;

        return DateTime::createFromFormat($format, $date, $timeZone);
    }

    /**
     * Generate ISO 8601 compliant date string in UTC time zone
     *
     * @param \DateTimeInterface $date The date value to convert
     *
     */
    public static function isoDate(DateTimeInterface $date): string
    {
        $date = clone $date;
        $date = $date->setTimezone(new DateTimeZone('UTC'));

        return str_replace('+00:00', 'Z', $date->format('c'));
    }

    /**
     * Converts a DateTime object into an Edm.DaeTime value in UTC timezone,
     * represented as a string.
     *
     * @param mixed $value The datetime value.
     *
     */
    public static function convertToEdmDateTime(mixed $value): string
    {
        if (empty($value)) {
            return $value;
        }

        if (is_string($value)) {
            $value =  self::convertToDateTime($value);
        }

        Validate::isDate($value);

        $cloned = clone $value;
        $cloned->setTimezone(new DateTimeZone('UTC'));
        return str_replace('+00:00', 'Z', $cloned->format("Y-m-d\TH:i:s.u0P"));
    }

    /**
     * Converts a string to a \DateTime object. Returns false on failure.
     *
     * @param string $value The string value to parse.
     *
     */
    public static function convertToDateTime(string $value): DateTime
    {
        if ($value instanceof DateTime) {
            return $value;
        }

        if (substr($value, -1) == 'Z') {
            $value = substr($value, 0, strlen($value) - 1);
        }

        return new DateTime($value, new DateTimeZone('UTC'));
    }

    /**
     * Converts string to stream handle.
     *
     * @param string $string The string contents.
     *
     * @return resource
     */
    public static function stringToStream(string $string)
    {
        return fopen('data://text/plain,' . urlencode($string), 'rb');
    }

    /**
     * Sorts an array based on given keys order.
     *
     * @param array $array The array to sort.
     * @param array $order The keys order array.
     *
     */
    public static function orderArray(array $array, array $order): array
    {
        $ordered = [];

        foreach ($order as $key) {
            if (array_key_exists($key, $array)) {
                $ordered[$key] = $array[$key];
            }
        }

        return $ordered;
    }

    /**
     * Checks if a value exists in an array. The comparison is done in a case
     * insensitive manner.
     *
     * @param string $needle   The searched value.
     * @param array  $haystack The array.
     *
     */
    public static function inArrayInsensitive(string $needle, array $haystack): bool
    {
        return in_array(strtolower($needle), array_map('strtolower', $haystack));
    }

    /**
     * Checks if the given key exists in the array. The comparison is done in a case
     * insensitive manner.
     *
     * @param string $key    The value to check.
     * @param array  $search The array with keys to check.
     *
     */
    public static function arrayKeyExistsInsensitive(string $key, array $search): bool
    {
        return array_key_exists(strtolower($key), array_change_key_case($search));
    }

    /**
     * Returns the specified value of the $key passed from $array and in case that
     * this $key doesn't exist, the default value is returned. The key matching is
     * done in a case insensitive manner.
     *
     * @param string $key      The array key.
     * @param array  $haystack The array to be used.
     * @param mixed  $default  The value to return if $key is not found in $array.
     *
     */
    public static function tryGetValueInsensitive(string $key, array $haystack, mixed $default = null): mixed
    {
        $array = array_change_key_case($haystack);
        return self::tryGetValue($array, strtolower($key), $default);
    }

    /**
     * Returns a string representation of a version 4 GUID, which uses random
     * numbers.There are 6 reserved bits, and the GUIDs have this format:
     *     xxxxxxxx-xxxx-4xxx-[8|9|a|b]xxx-xxxxxxxxxxxx
     * where 'x' is a hexadecimal digit, 0-9a-f.
     *
     * See http://tools.ietf.org/html/rfc4122 for more information.
     *
     * Note: This function is available on all platforms, while the
     * com_create_guid() is only available for Windows.
     *
     * @return string A new GUID.
     */
    public static function getGuid(): string
    {
        // @codingStandardsIgnoreStart

        return sprintf(
            '%04x%04x-%04x-%04x-%02x%02x-%04x%04x%04x',
            mt_rand(0, 65535),
            mt_rand(0, 65535),          // 32 bits for "time_low"
            mt_rand(0, 65535),          // 16 bits for "time_mid"
            mt_rand(0, 4096) + 16384,   // 16 bits for "time_hi_and_version", with
                                        // the most significant 4 bits being 0100
                                        // to indicate randomly generated version
            mt_rand(0, 64) + 128,       // 8 bits  for "clock_seq_hi", with
                                        // the most significant 2 bits being 10,
                                        // required by version 4 GUIDs.
            mt_rand(0, 255),            // 8 bits  for "clock_seq_low"
            mt_rand(0, 65535),          // 16 bits for "node 0" and "node 1"
            mt_rand(0, 65535),          // 16 bits for "node 2" and "node 3"
            mt_rand(0, 65535)           // 16 bits for "node 4" and "node 5"
        );

        // @codingStandardsIgnoreEnd
    }

    /**
     * Creates a list of objects of type $class from the provided array using static
     * create method.
     *
     * @param array  $parsed The object in array representation
     * @param string $class  The class name. Must have static method create.
     *
     */
    public static function createInstanceList(array $parsed, string $class): array
    {
        $list = [];

        foreach ($parsed as $value) {
            $list[] = $class::create($value);
        }

        return $list;
    }

    /**
     * Takes a string and return if it ends with the specified character/string.
     *
     * @param string  $haystack   The string to search in.
     * @param string  $needle     postfix to match.
     * @param bool $ignoreCase Set true to ignore case during the comparison;
     * otherwise, false
     *
     */
    public static function endsWith(string $haystack, string $needle, bool $ignoreCase = false): bool
    {
        if ($ignoreCase) {
            $haystack = strtolower($haystack);
            $needle   = strtolower($needle);
        }
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }

        return substr($haystack, -$length) === $needle;
    }

    /**
     * Get id from entity object or string.
     * If entity is object than validate type and return $entity->$method()
     * If entity is string than return this string
     *
     * @param object|string $entity Entity with id property
     * @param string        $type   Entity type to validate
     * @param string        $method Methods that gets id (getId by default)
     *
     */
    public static function getEntityId(object|string $entity, string $type, string $method = 'getId'): string
    {
        if (is_string($entity)) {
            return $entity;
        }

        Validate::isA($entity, $type, 'entity');
        Validate::methodExists($entity, $method, $type);

        return $entity->$method();
    }

    /**
     * Generate a pseudo-random string of bytes using a cryptographically strong
     * algorithm.
     *
     * @param int $length Length of the string in bytes
     *
     * @return string|bool Generated string of bytes on success, or FALSE on
     *                        failure.
     */
    public static function generateCryptoKey(int $length): string|bool
    {
        return openssl_random_pseudo_bytes($length);
    }

    /**
     * Convert base 256 number to decimal number.
     *
     * @param string $number Base 256 number
     *
     * @return string Decimal number
     */
    public static function base256ToDec(string $number): string
    {
        Validate::canCastAsString($number, 'number');

        $result = 0;
        $base   = 1;
        for ($i = strlen($number) - 1; $i >= 0; $i--) {
            $result = bcadd($result, bcmul(ord($number[$i]), $base));
            $base   = bcmul($base, 256);
        }

        return $result;
    }

    /**
     * To evaluate if the stream is larger than a certain size. To restore
     * the stream, it has to be seekable, so will return true if the stream
     * is not seekable.
     *
     * @param  StreamInterface $stream The stream to be evaluated.
     * @param  int             $size   The size if the string is larger than.
     *
     * @return bool         true if the stream is larger than the given size.
     */
    public static function isStreamLargerThanSizeOrNotSeekable(StreamInterface $stream, int $size): bool
    {
        Validate::isInteger($size, 'size');
        Validate::isTrue(
            $stream instanceof StreamInterface,
            sprintf(Resources::INVALID_PARAM_MSG, 'stream', 'Psr\Http\Message\StreamInterface')
        );
        $result = true;
        if ($stream->isSeekable()) {
            $position = $stream->tell();
            try {
                $stream->seek($size);
            } catch (RuntimeException $e) {
                $pos = strpos(
                    $e->getMessage(),
                    'to seek to stream position '
                );
                if ($pos == null) {
                    throw $e;
                }
                $result = false;
            }
            if ($stream->eof()) {
                $result = false;
            } elseif ($stream->read(1) == '') {
                $result = false;
            }
            $stream->seek($position);
        }
        return $result;
    }

    /**
     * Gets metadata array by parsing them from given headers.
     *
     * @param array $headers HTTP headers containing metadata elements.
     *
     */
    public static function getMetadataArray(array $headers): array
    {
        $metadata = [];
        foreach ($headers as $key => $value) {
            $isMetadataHeader = self::startsWith(
                strtolower($key),
                Resources::X_MS_META_HEADER_PREFIX
            );

            if ($isMetadataHeader) {
                // Metadata name is case-presrved and case insensitive
                $MetadataName = str_ireplace(
                    Resources::X_MS_META_HEADER_PREFIX,
                    Resources::EMPTY_STRING,
                    $key
                );
                $metadata[$MetadataName] = $value;
            }
        }

        return $metadata;
    }

    /**
     * Validates the provided metadata array.
     *
     * @param array $metadata The metadata array.
     *
     */
    public static function validateMetadata(?array $metadata = null): void
    {
        if (!is_null($metadata)) {
            Validate::isArray($metadata, 'metadata');
        } else {
            $metadata = [];
        }

        foreach ($metadata as $key => $value) {
            Validate::canCastAsString($key, 'metadata key');
            Validate::canCastAsString($value, 'metadata value');
        }
    }

    /**
     * Append the content to file.
     *
     * @param  string $path    The file to append to.
     * @param  string $content The content to append.
     *
     */
    public static function appendToFile(string $path, string $content): void
    {
        $resource = @fopen($path, 'a+');
        if ($resource != null) {
            fwrite($resource, $content);
            fclose($resource);
        }
    }

    /**
     * Check if all the bytes are zero.
     *
     * @param string $content The content.
     */
    public static function allZero(string $content): bool
    {
        $size = strlen($content);

        // If all Zero, skip this range
        for ($i = 0; $i < $size; $i++) {
            if (ord($content[$i]) != 0) {
                return false;
            }
        }

        return true;
    }

    /**
     * Append the delimiter to the string. The delimiter will not be added if
     * the string already ends with this delimiter.
     *
     * @param string $string    The string to add delimiter to.
     * @param string $delimiter The delimiter to be added.
     *
     */
    public static function appendDelimiter(string $string, string $delimiter): string
    {
        if (!self::endsWith($string, $delimiter)) {
            $string .= $delimiter;
        }

        return $string;
    }

    /**
     * Static function used to determine if the request is performed against
     * secondary endpoint.
     *
     * @param  Psr\Http\Message\RequestInterface $request The request performed.
     * @param  array                             $options The options of the
     *                                                    request. Must contain
     *                                                    Resources::ROS_SECONDARY_URI
     *
     */
    public static function requestSentToSecondary(
        RequestInterface $request,
        array $options
    ): bool {
        $uri = $request->getUri();
        $secondaryUri = $options[Resources::ROS_SECONDARY_URI];
        $isSecondary = false;
        if (strpos((string) $uri, (string) $secondaryUri) !== false) {
            $isSecondary = true;
        }
        return $isSecondary;
    }

    /**
     * Gets the location value from the headers.
     *
     * @param  array  $headers request/response headers.
     *
     */
    public static function getLocationFromHeaders(array $headers): string
    {
        $value = self::tryGetValue(
            $headers,
            Resources::X_MS_CONTINUATION_LOCATION_MODE
        );

        $result = '';
        if (is_string($value)) {
            $result = $value;
        } elseif (!empty($value)) {
            $result = $value[0];
        }
        return $result;
    }

    /**
     * Gets if the value is a double value or string representation of a double
     * value
     *
     * @param  mixed  $value The value to be verified.
     *
     */
    public static function isDouble(mixed $value): bool
    {
        return is_numeric($value) && is_double($value + 0);
    }

    /**
     * Calculates the content MD5 which is base64 encoded. This should be align
     * with the server calculated MD5.
     *
     * @param  string $content the content to be calculated.
     *
     */
    public static function calculateContentMD5(string $content): string
    {
        Validate::notNull($content, 'content');
        Validate::canCastAsString($content, 'content');

        return base64_encode(md5($content, true));
    }

    /**
     * Return if the environment is in 64 bit PHP.
     *
     */
    public static function is64BitPHP(): bool
    {
        return PHP_INT_SIZE == 8;
    }

}
