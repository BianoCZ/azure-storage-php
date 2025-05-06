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
 * @package   MicrosoftAzure\Storage\Common\Exceptions
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */

namespace MicrosoftAzure\Storage\Common\Exceptions;

use Exception;
use LogicException;
use MicrosoftAzure\Storage\Common\Internal\Resources;
use MicrosoftAzure\Storage\Common\Internal\Serialization\XmlSerializer;
use Psr\Http\Message\ResponseInterface;
use Throwable;
use function array_key_exists;
use function implode;
use function libxml_get_errors;
use function libxml_use_internal_errors;
use function sprintf;

/**
 * Fires when the response code is incorrect.
 *
 * @category  Microsoft
 * @package   MicrosoftAzure\Storage\Common\Exceptions
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2016 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */
class ServiceException extends LogicException
{

    private $response;

    private $errorText;

    private $errorMessage;

    /**
     * Constructor
     *
     * @param ResponseInterface $response The response received that causes the
     *                                    exception.
     *
     * @internal
     *
     * @return ServiceException
     */
    public function __construct(ResponseInterface $response)
    {
        parent::__construct(
            sprintf(
                Resources::AZURE_ERROR_MSG,
                $response->getStatusCode(),
                $response->getReasonPhrase(),
                $response->getBody()
            )
        );

        $this->code         = $response->getStatusCode();
        $this->response     = $response;
        $this->errorText    = $response->getReasonPhrase();
        $this->errorMessage = self::parseErrorMessage($response);
    }

    /**
     * Error message to be parsed.
     *
     * @param  ResponseInterface $response The response with a response body.
     *
     * @internal
     *
     */
    protected static function parseErrorMessage(ResponseInterface $response): string
    {
        //try to parse using xml serializer, if failed, return the whole body
        //as the error message.
        $serializer = new XmlSerializer();
        $errorMessage = '';
        try {
            $internalErrors = libxml_use_internal_errors(true);
            $parsedArray = $serializer->unserialize($response->getBody());
            $messages = [];
            foreach (libxml_get_errors() as $error) {
                $messages[] = $error->message;
            }
            if (!empty($messages)) {
                throw new Exception(
                    sprintf(Resources::ERROR_CANNOT_PARSE_XML, implode('; ', $messages))
                );
            }
            libxml_use_internal_errors($internalErrors);
            if (array_key_exists(Resources::XTAG_MESSAGE, $parsedArray)) {
                $errorMessage = $parsedArray[Resources::XTAG_MESSAGE];
            } else {
                $errorMessage = $response->getBody();
            }
        } catch (Throwable) {
            $errorMessage = $response->getBody();
        }
        return $errorMessage;
    }

    /**
     * Gets error text.
     *
     */
    public function getErrorText(): string
    {
        return $this->errorText;
    }

    /**
     * Gets detailed error message.
     *
     */
    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    /**
     * Gets the request ID of the failure.
     *
     */
    public function getRequestID(): string
    {
        $requestID = '';
        if (
            array_key_exists(
                Resources::X_MS_REQUEST_ID,
                $this->getResponse()->getHeaders()
            )
        ) {
            $requestID = $this->getResponse()
                ->getHeaders()[Resources::X_MS_REQUEST_ID][0];
        }
        return $requestID;
    }

    /**
     * Gets the Date of the failure.
     *
     */
    public function getDate(): string
    {
        $date = '';
        if (
            array_key_exists(
                Resources::DATE,
                $this->getResponse()->getHeaders()
            )
        ) {
            $date = $this->getResponse()
                ->getHeaders()[Resources::DATE][0];
        }
        return $date;
    }

    /**
     * Gets the response of the failue.
     *
     */
    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }

}
