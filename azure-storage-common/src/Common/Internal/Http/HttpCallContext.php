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
 * @package   MicrosoftAzure\Storage\Common\Internal\Http
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */

namespace MicrosoftAzure\Storage\Common\Internal\Http;

use MicrosoftAzure\Storage\Common\Internal\Resources;
use MicrosoftAzure\Storage\Common\Internal\Utilities;
use MicrosoftAzure\Storage\Common\Internal\Validate;
use MicrosoftAzure\Storage\Common\Models\ServiceOptions;
use function strlen;

/**
 * Holds basic elements for making HTTP call.
 *
 * @ignore
 * @category  Microsoft
 * @package   MicrosoftAzure\Storage\Common\Internal\Http
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */
class HttpCallContext
{

    private $_method;

    private $_headers;

    private $_queryParams;

    private $_postParameters;

    private $_uri;

    private $_path;

    private $_statusCodes;

    private $_body;

    private $_serviceOptions;

    /**
     * Default constructor.
     */
    public function __construct()
    {
        $this->_method         = null;
        $this->_body           = null;
        $this->_path           = null;
        $this->_uri            = null;
        $this->_queryParams    = [];
        $this->_postParameters = [];
        $this->_statusCodes    = [];
        $this->_headers        = [];
        $this->_serviceOptions = new ServiceOptions();
    }

    /**
     * Gets method.
     *
     */
    public function getMethod(): string
    {
        return $this->_method;
    }

    /**
     * Sets method.
     *
     * @param string $method The method value.
     *
     */
    public function setMethod(string $method): void
    {
        Validate::canCastAsString($method, 'method');

        $this->_method = $method;
    }

    /**
     * Gets headers.
     *
     */
    public function getHeaders(): array
    {
        return $this->_headers;
    }

    /**
     * Sets headers.
     *
     * Ignores the header if its value is empty.
     *
     * @param array $headers The headers value.
     *
     */
    public function setHeaders(array $headers): void
    {
        $this->_headers = [];
        foreach ($headers as $key => $value) {
            $this->addHeader($key, $value);
        }
    }

    /**
     * Gets queryParams.
     *
     */
    public function getQueryParameters(): array
    {
        return $this->_queryParams;
    }

    /**
     * Sets queryParams.
     *
     * Ignores the query variable if its value is empty.
     *
     * @param array $queryParams The queryParams value.
     *
     */
    public function setQueryParameters(array $queryParams): void
    {
        $this->_queryParams = [];
        foreach ($queryParams as $key => $value) {
            $this->addQueryParameter($key, $value);
        }
    }

    /**
     * Gets uri.
     *
     */
    public function getUri(): string
    {
        return $this->_uri;
    }

    /**
     * Sets uri.
     *
     * @param string $uri The uri value.
     *
     */
    public function setUri(string $uri): void
    {
        Validate::canCastAsString($uri, 'uri');

        $this->_uri = $uri;
    }

    /**
     * Gets path.
     *
     */
    public function getPath(): string
    {
        return $this->_path;
    }

    /**
     * Sets path.
     *
     * @param string $path The path value.
     *
     */
    public function setPath(string $path): void
    {
        Validate::canCastAsString($path, 'path');

        $this->_path = $path;
    }

    /**
     * Gets statusCodes.
     *
     */
    public function getStatusCodes(): array
    {
        return $this->_statusCodes;
    }

    /**
     * Sets statusCodes.
     *
     * @param array $statusCodes The statusCodes value.
     *
     */
    public function setStatusCodes(array $statusCodes): void
    {
        $this->_statusCodes = [];
        foreach ($statusCodes as $value) {
            $this->addStatusCode($value);
        }
    }

    /**
     * Gets body.
     *
     */
    public function getBody(): string
    {
        return $this->_body;
    }

    /**
     * Sets body.
     *
     * @param string $body The body value.
     *
     */
    public function setBody(string $body): void
    {
        Validate::canCastAsString($body, 'body');

        $this->_body = $body;
    }

    /**
     * Adds or sets header pair.
     *
     * @param string $name  The HTTP header name.
     * @param string $value The HTTP header value.
     *
     */
    public function addHeader(string $name, string $value): void
    {
        Validate::canCastAsString($name, 'name');
        Validate::canCastAsString($value, 'value');

        $this->_headers[$name] = $value;
    }

    /**
     * Adds or sets header pair.
     *
     * Ignores header if it's value satisfies empty().
     *
     * @param string $name  The HTTP header name.
     * @param string $value The HTTP header value.
     *
     */
    public function addOptionalHeader(string $name, string $value): void
    {
        Validate::canCastAsString($name, 'name');
        Validate::canCastAsString($value, 'value');

        if (!empty($value)) {
            $this->_headers[$name] = $value;
        }
    }

    /**
     * Removes header from the HTTP request headers.
     *
     * @param string $name The HTTP header name.
     *
     */
    public function removeHeader(string $name): void
    {
        Validate::canCastAsString($name, 'name');
        Validate::notNullOrEmpty($name, 'name');

        unset($this->_headers[$name]);
    }

    /**
     * Adds or sets query parameter pair.
     *
     * @param string $name  The URI query parameter name.
     * @param string $value The URI query parameter value.
     *
     */
    public function addQueryParameter(string $name, string $value): void
    {
        Validate::canCastAsString($name, 'name');
        Validate::canCastAsString($value, 'value');

        $this->_queryParams[$name] = $value;
    }

    /**
     * Gets HTTP POST parameters.
     *
     */
    public function getPostParameters(): array
    {
        return $this->_postParameters;
    }

    /**
     * Sets HTTP POST parameters.
     *
     * @param array $postParameters The HTTP POST parameters.
     *
     */
    public function setPostParameters(array $postParameters): void
    {
        Validate::isArray($postParameters, 'postParameters');
        $this->_postParameters = $postParameters;
    }

    /**
     * Adds or sets query parameter pair.
     *
     * Ignores query parameter if it's value satisfies empty().
     *
     * @param string $name  The URI query parameter name.
     * @param string $value The URI query parameter value.
     *
     */
    public function addOptionalQueryParameter(string $name, string $value): void
    {
        Validate::canCastAsString($name, 'name');
        Validate::canCastAsString($value, 'value');

        if (!empty($value)) {
            $this->_queryParams[$name] = $value;
        }
    }

    /**
     * Adds status code to the expected status codes.
     *
     * @param int $statusCode The expected status code.
     *
     */
    public function addStatusCode(int $statusCode): void
    {
        Validate::isInteger($statusCode, 'statusCode');

        $this->_statusCodes[] = $statusCode;
    }

    /**
     * Gets header value.
     *
     * @param string $name The header name.
     *
     */
    public function getHeader(string $name): mixed
    {
        return Utilities::tryGetValue($this->_headers, $name);
    }

    /**
     * Gets the saved service options
     *
     */
    public function getServiceOptions(): ServiceOptions
    {
        if ($this->_serviceOptions == null) {
            $this->_serviceOptions = new ServiceOptions();
        }
        return $this->_serviceOptions;
    }

    /**
     * Sets the service options
     *
     * @param ServiceOptions $serviceOptions the service options to be set.
     *
     */
    public function setServiceOptions(ServiceOptions $serviceOptions): void
    {
        $this->_serviceOptions = $serviceOptions;
    }

    /**
     * Converts the context object to string.
     *
     */
    public function __toString(): string
    {
        $headers = Resources::EMPTY_STRING;
        $uri     = $this->_uri;

        if ($uri === null) {
            $uri = '/';
        } elseif ($uri[strlen($uri) - 1] != '/') {
            $uri .= '/';
        }

        foreach ($this->_headers as $key => $value) {
            $headers .= "$key: $value\n";
        }

        $str  = "$this->_method $uri$this->_path HTTP/1.1\n$headers\n";
        $str .= "$this->_body";

        return $str;
    }

}
