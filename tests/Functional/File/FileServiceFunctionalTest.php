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
 * @package   MicrosoftAzure\Storage\Tests\Functional\File
 * @author    Azure Storage PHP SDK <dmsh@microsoft.com>
 * @copyright 2017 Microsoft Corporation
 * @license   https://github.com/azure/azure-storage-php/LICENSE
 * @link      https://github.com/azure/azure-storage-php
 */

namespace MicrosoftAzure\Storage\Tests\Functional\File;

use DateTime;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Common\Internal\Utilities;
use MicrosoftAzure\Storage\Common\Middlewares\HistoryMiddleware;
use MicrosoftAzure\Storage\Common\Middlewares\RetryMiddlewareFactory;
use MicrosoftAzure\Storage\Common\Models\Range;
use MicrosoftAzure\Storage\File\FileRestProxy;
use MicrosoftAzure\Storage\File\Models\CreateFileFromContentOptions;
use MicrosoftAzure\Storage\File\Models\CreateShareOptions;
use MicrosoftAzure\Storage\File\Models\FileServiceOptions;
use MicrosoftAzure\Storage\File\Models\GetFileOptions;
use MicrosoftAzure\Storage\File\Models\ListDirectoriesAndFilesOptions;
use MicrosoftAzure\Storage\File\Models\ListSharesOptions;
use MicrosoftAzure\Storage\File\Models\PutFileRangeOptions;
use MicrosoftAzure\Storage\Tests\Framework\TestResources;
use function array_keys;
use function count;
use function is_null;
use function rand;
use function sleep;
use function sprintf;
use function stream_get_contents;
use function strlen;
use function strtolower;
use function uniqid;

class FileServiceFunctionalTest extends FunctionalTestBase
{
    public function testGetServicePropertiesNoOptions(): void
    {
        $serviceProperties = FileServiceFunctionalTestData::getDefaultServiceProperties();
        $this->restProxy->setServiceProperties($serviceProperties);
        $this->getServicePropertiesWorker(null);
    }

    public function testGetServiceProperties(): void
    {
        $serviceProperties = FileServiceFunctionalTestData::getDefaultServiceProperties();

        $shouldReturn = false;
        try {
            $this->restProxy->setServiceProperties($serviceProperties);
            $this->assertFalse($this->isEmulated(), 'Should succeed when not running in emulator');
        } catch (ServiceException $e) {
            // Expect failure in emulator, as v1.6 doesn't support this method
            if ($this->isEmulated()) {
                $this->assertEquals(TestResources::STATUS_BAD_REQUEST, $e->getCode(), 'getCode');
                $shouldReturn = true;
            } else {
                throw $e;
            }
        }
        if ($shouldReturn) {
            return;
        }

        // Now look at the combos.
        $interestingTimeouts = FileServiceFunctionalTestData::getInterestingTimeoutValues();
        foreach ($interestingTimeouts as $timeout) {
            $options = new FileServiceOptions();
            $options->setTimeout($timeout);
            $this->getServicePropertiesWorker($options);
        }
    }

    private function getServicePropertiesWorker($options): void
    {
        $options = (is_null($options) ? new FileServiceOptions() : $options);
        try {
            $ret = $this->restProxy->getServiceProperties($options);

            if (!is_null($options->getTimeout()) && $options->getTimeout() < 1) {
                $this->assertTrue(false, 'Expect negative timeouts in $options to throw');
            }
            $this->verifyServicePropertiesWorker($ret, null);
        } catch (ServiceException $e) {
            if (is_null($options->getTimeout()) || $options->getTimeout() >= 1) {
                throw $e;
            }

            $this->assertEquals(
                TestResources::STATUS_INTERNAL_SERVER_ERROR,
                $e->getCode(),
                'getCode'
            );
        }
    }

    private function verifyServicePropertiesWorker($ret, $serviceProperties): void
    {
        if (is_null($serviceProperties)) {
            $serviceProperties = FileServiceFunctionalTestData::getDefaultServiceProperties();
        }

        $sp = $ret->getValue();
        $this->assertNotNull($sp, 'getValue should be non-null');

        $m = $sp->getHourMetrics();
        $this->assertNotNull(
            $m,
            'getValue()->getHourMetrics() should be non-null'
        );
        $this->assertEquals(
            $serviceProperties->getHourMetrics()->getVersion(),
            $m->getVersion(),
            'getValue()->getHourMetrics()->getVersion'
        );
        $this->assertEquals(
            $serviceProperties->getHourMetrics()->getEnabled(),
            $m->getEnabled(),
            'getValue()->getHourMetrics()->getEnabled'
        );
        $this->assertEquals(
            $serviceProperties->getHourMetrics()->getIncludeAPIs(),
            $m->getIncludeAPIs(),
            'getValue()->getHourMetrics()->getIncludeAPIs'
        );

        $r = $m->getRetentionPolicy();
        $this->assertNotNull(
            $r,
            'getValue()->getHourMetrics()->getRetentionPolicy should be non-null'
        );
        $this->assertEquals(
            $serviceProperties->getHourMetrics()->getRetentionPolicy()->getDays(),
            $r->getDays(),
            'getValue()->getHourMetrics()->getRetentionPolicy()->getDays'
        );
    }

    public function testSetServicePropertiesNoOptions(): void
    {
        $serviceProperties = FileServiceFunctionalTestData::getDefaultServiceProperties();
        $this->setServicePropertiesWorker($serviceProperties, null);
    }

    public function testSetServiceProperties(): void
    {
        $interestingServiceProperties =
            FileServiceFunctionalTestData::getInterestingServiceProperties();
        foreach ($interestingServiceProperties as $serviceProperties) {
            $interestingTimeouts = FileServiceFunctionalTestData::getInterestingTimeoutValues();
            foreach ($interestingTimeouts as $timeout) {
                $options = new FileServiceOptions();
                $options->setTimeout($timeout);
                $this->setServicePropertiesWorker($serviceProperties, $options);
            }
        }
    }

    private function setServicePropertiesWorker($serviceProperties, $options): void
    {
        try {
            if (is_null($options)) {
                $this->restProxy->setServiceProperties($serviceProperties);
            } else {
                $this->restProxy->setServiceProperties($serviceProperties, $options);
            }

            if (is_null($options)) {
                $options = new FileServiceOptions();
            }

            if (!is_null($options->getTimeout()) && $options->getTimeout() < 1) {
                $this->assertTrue(false, 'Expect negative timeouts in $options to throw');
            }

            sleep(10);

            $ret = $this->restProxy->getServiceProperties($options);
            $this->verifyServicePropertiesWorker($ret, $serviceProperties);
        } catch (ServiceException $e) {
            if (is_null($options)) {
                $options = new FileServiceOptions();
            }

            if ($this->isEmulated()) {
                if (!is_null($options->getTimeout()) && $options->getTimeout() < 1) {
                    $this->assertEquals(TestResources::STATUS_INTERNAL_SERVER_ERROR, $e->getCode(), 'getCode');
                } else {
                    $this->assertEquals(TestResources::STATUS_BAD_REQUEST, $e->getCode(), 'getCode');
                }
            } else {
                if (!is_null($options->getTimeout()) && $options->getTimeout() < 1) {
                    $this->assertEquals(TestResources::STATUS_INTERNAL_SERVER_ERROR, $e->getCode(), 'getCode');
                } else {
                    throw $e;
                }
            }
        }
    }

    public function testListSharesNoOptions(): void
    {
        $this->listSharesWorker(null);
    }

    public function testListShares(): void
    {
        $interestingListSharesOptions = FileServiceFunctionalTestData::getInterestingListSharesOptions();
        foreach ($interestingListSharesOptions as $options) {
            $this->listSharesWorker($options);
        }
    }

    private function listSharesWorker($options): void
    {
        $finished = false;
        while (!$finished) {
            try {
                $ret = (is_null($options) ?
                    $this->restProxy->listShares() :
                    $this->restProxy->listShares($options)
                );

                if (is_null($options)) {
                    $options = new ListSharesOptions();
                }

                if (!is_null($options->getTimeout()) && $options->getTimeout() < 1) {
                    $this->assertTrue(false, 'Expect negative timeouts in $options to throw');
                }
                $this->verifyListSharesWorker($ret, $options);

                if (strlen($ret->getNextMarker()) == 0) {
                    $finished = true;
                } else {
                    $options->setMarker($ret->getNextMarker());
                }
            } catch (ServiceException $e) {
                $finished = true;
                if (is_null($options->getTimeout()) || $options->getTimeout() >= 1) {
                    throw $e;
                }

                $this->assertEquals(TestResources::STATUS_INTERNAL_SERVER_ERROR, $e->getCode(), 'getCode');
            }
        }
    }

    private function verifyListSharesWorker($ret, $options): void
    {
        // Cannot really check the next marker. Just make sure it is not null.
        $this->assertEquals($options->getNextMarker(), $ret->getMarker(), 'getNextMarker');
        $this->assertEquals($options->getMaxResults(), $ret->getMaxResults(), 'getMaxResults');
        $this->assertEquals($options->getPrefix(), $ret->getPrefix(), 'getPrefix');

        $this->assertNotNull($ret->getShares(), 'getFiles');
        if ($options->getMaxResults() == 0) {
            $this->assertEquals(
                0,
                strlen($ret->getNextMarker()),
                'When MaxResults is 0, expect getNextMarker (' .
                    strlen($ret->getNextMarker()) . ')to be  '
            );

            if (
                !is_null($options->getPrefix()) &&
                $options->getPrefix() ==
                    FileServiceFunctionalTestData::$nonExistFilePrefix
            ) {
                $this->assertEquals(
                    0,
                    count($ret->getShares()),
                    'when MaxResults=0 and Prefix=(\'' .
                        $options->getPrefix() . '\'), then Files length'
                );
            } elseif (
                !is_null($options->getPrefix()) &&
                $options->getPrefix() ==
                    FileServiceFunctionalTestData::$testUniqueId
            ) {
                $this->assertEquals(
                    FileServiceFunctionalTestData::$trackedShareCount,
                    count(
                        $ret->getShares()
                    ),
                    'when MaxResults=0 and Prefix=(\'' .
                        $options->getPrefix() .
                        '\'), then Files length'
                );
            } else {
                // Do not know how many there should be
            }
        } elseif (strlen($ret->getNextMarker()) == 0) {
            $this->assertTrue(
                count($ret->getShares()) <= $options->getMaxResults(),
                'when NextMarker (\'' . $ret->getNextMarker() . '\')==\'\',
                Files length (' . count($ret->getShares()) .
                ') should be <= MaxResults (' . $options->getMaxResults() .
                ')'
            );

            if (
                FileServiceFunctionalTestData::$nonExistFilePrefix ==
                    $options->getPrefix()
            ) {
                $this->assertEquals(
                    0,
                    count($ret->getShares()),
                    'when no next marker and Prefix=(\'' .
                        $options->getPrefix() . '\'), then Files length'
                );
            } elseif (
                FileServiceFunctionalTestData::$testUniqueId ==
                    $options->getPrefix()
            ) {
                // Need to futz with the mod because you are allowed to get MaxResults items returned.
                $expectedCount =
                    FileServiceFunctionalTestData::$trackedShareCount % $options->getMaxResults();
                $this->assertEquals(
                    $expectedCount,
                    count($ret->getShares()),
                    'when no next marker and Prefix=(\'' .
                    $options->getPrefix() . '\'), then Files length'
                );
            } else {
                // Do not know how many there should be
            }
        } else {
            $this->assertEquals(
                count($ret->getShares()),
                $options->getMaxResults(),
                'when NextMarker (' . $ret->getNextMarker() .
                ')!=\'\', Files length (' . count($ret->getShares()) .
                ') should be == MaxResults (' . $options->getMaxResults() .
                ')'
            );
            if (
                !is_null($options->getPrefix()) &&
                $options->getPrefix() ==
                    FileServiceFunctionalTestData::$nonExistFilePrefix
            ) {
                $this->assertTrue(
                    false,
                    'when a next marker and Prefix=(\'' .
                        $options->getPrefix() . '\'), impossible'
                );
            }
        }
    }

    public function testCreateShareNoOptions(): void
    {
        $this->createShareWorker(null);
    }

    public function testCreateShare(): void
    {
        $interestingCreateShareOptions = FileServiceFunctionalTestData::getInterestingCreateShareOptions();
        foreach ($interestingCreateShareOptions as $options) {
            $this->createShareWorker($options);
        }
    }

    private function createShareWorker($options): void
    {
        $share = FileServiceFunctionalTestData::getInterestingShareName();
        $created = false;

        try {
            if (is_null($options)) {
                $this->restProxy->createShare($share);
            } else {
                $this->restProxy->createShare($share, $options);
            }
            $created = true;

            if (is_null($options)) {
                $options = new CreateShareOptions();
            }

            if (!is_null($options->getTimeout()) && $options->getTimeout() < 1) {
                $this->assertTrue(false, 'Expect negative timeouts in $options to throw');
            }

            // Now check that the $share was $created correctly.

            // Make sure that the list of all applicable shares is correctly updated.
            $opts = new ListSharesOptions();
            $opts->setPrefix(FileServiceFunctionalTestData::$testUniqueId);
            $qs = $this->restProxy->listShares($opts);
            $this->assertEquals(
                count($qs->getShares()),
                FileServiceFunctionalTestData::$trackedShareCount + 1,
                'After adding one, with Prefix=(\'' . FileServiceFunctionalTestData::$testUniqueId .
                    '\'), then Shares length'
            );

            // Check the metadata on the share
            $ret = $this->restProxy->getShareMetadata($share);
            $this->verifyCreateShareWorker($ret, $options);
            $this->restProxy->deleteShare($share);
        } catch (ServiceException $e) {
            if (is_null($options)) {
                $options = new CreateShareOptions();
            }

            if (is_null($options->getTimeout()) || $options->getTimeout() >= 1) {
                throw $e;
            }

            $this->assertEquals(TestResources::STATUS_INTERNAL_SERVER_ERROR, $e->getCode(), 'getCode');
        }

        if ($created) {
            try {
                $this->restProxy->deleteShare($share);
            } catch (ServiceException) {
                // Ignore.
            }
        }
    }

    private function verifyCreateShareWorker($ret, $options): void
    {
        if (is_null($options->getMetadata())) {
            $this->assertNotNull($ret->getMetadata(), 'share Metadata');
            $this->assertEquals(0, count($ret->getMetadata()), 'count share Metadata');
        } else {
            $this->assertNotNull($ret->getMetadata(), 'share Metadata');
            $this->assertEquals(count($options->getMetadata()), count($ret->getMetadata()), 'Metadata');
            $retMetadata = $ret->getMetadata();
            foreach ($options->getMetadata() as $key => $value) {
                $this->assertEquals($value, $retMetadata[$key], 'Metadata(' . $key . ')');
            }
        }
    }

    public function testDeleteShareNoOptions(): void
    {
        $this->deleteShareWorker(null);
    }

    public function testDeleteShare(): void
    {
        $interestingDeleteShareOptions = FileServiceFunctionalTestData::getInterestingDeleteShareOptions();
        foreach ($interestingDeleteShareOptions as $options) {
            $this->deleteShareWorker($options);
        }
    }

    private function deleteShareWorker($options): void
    {
        $share = FileServiceFunctionalTestData::getInterestingShareName();

        // Make sure there is something to delete.
        $this->restProxy->createShare($share);

        // Make sure that the list of all applicable shares is correctly updated.
        $opts = new ListSharesOptions();
        $opts->setPrefix(FileServiceFunctionalTestData::$testUniqueId);
        $qs = $this->restProxy->listShares($opts);
        $this->assertEquals(
            count($qs->getShares()),
            FileServiceFunctionalTestData::$trackedShareCount + 1,
            'After adding one, with Prefix=(\'' .
                FileServiceFunctionalTestData::$testUniqueId .
                '\'), then Shares length'
        );

        $deleted = false;
        try {
            if (is_null($options)) {
                $this->restProxy->deleteShare($share);
            } else {
                $this->restProxy->deleteShare($share, $options);
            }

            $deleted = true;

            if (is_null($options)) {
                $options = new FileServiceOptions();
            }

            if (!is_null($options->getTimeout()) && $options->getTimeout() < 1) {
                $this->assertTrue(false, 'Expect negative timeouts in $options to throw');
            }

            // Make sure that the list of all applicable shares is correctly updated.
            $opts = new ListSharesOptions();
            $opts->setPrefix(FileServiceFunctionalTestData::$testUniqueId);
            $qs = $this->restProxy->listShares($opts);
            $this->assertEquals(
                count($qs->getShares()),
                FileServiceFunctionalTestData::$trackedShareCount,
                'After adding then deleting one, with Prefix=(\'' .
                    FileServiceFunctionalTestData::$testUniqueId .
                    '\'), then Shares length'
            );

            // Nothing else interesting to check for the $options.
        } catch (ServiceException $e) {
            if (is_null($options)) {
                $options = new FileServiceOptions();
            }

            if (!is_null($options->getTimeout()) && $options->getTimeout() < 1) {
                $this->assertEquals(TestResources::STATUS_INTERNAL_SERVER_ERROR, $e->getCode(), 'getCode');
            } elseif (
                !$this->isEmulated() &&
                    !FileServiceFunctionalTestData::passTemporalAccessCondition(
                        $options->getAccessConditions()
                    )
            ) {
                $this->assertEquals(TestResources::STATUS_PRECONDITION_FAILED, $e->getCode(), 'getCode');
            } else {
                throw $e;
            }
        }

        if (!$deleted) {
            // Try again. If it does not work, not much else to try.
            $this->restProxy->deleteShare($share);
        }
    }

    public function testGetShareMetadataNoOptions(): void
    {
        $metadata = FileServiceFunctionalTestData::getNiceMetadata();
        $this->getShareMetadataWorker(null, $metadata);
    }

    public function testGetShareMetadata(): void
    {
        $interestingTimeouts = FileServiceFunctionalTestData::getInterestingTimeoutValues();
        $metadata = FileServiceFunctionalTestData::getNiceMetadata();

        foreach ($interestingTimeouts as $timeout) {
            $options = new FileServiceOptions();
            $options->setTimeout($timeout);
            $this->getShareMetadataWorker($options, $metadata);
        }
    }

    private function getShareMetadataWorker($options, $metadata): void
    {
        $share = FileServiceFunctionalTestData::getInterestingShareName();

        // Make sure there is something to test
        $this->restProxy->createShare($share);
        $this->restProxy->setShareMetadata($share, $metadata);

        try {
            $res = (is_null($options) ?
                $this->restProxy->getShareMetadata($share) :
                $this->restProxy->getShareMetadata($share, $options));

            if (is_null($options)) {
                $options = new FileServiceOptions();
            }

            if (!is_null($options->getTimeout()) && $options->getTimeout() <= 0) {
                $this->assertTrue(false, 'Expect negative timeouts in $options to throw');
            }

            $this->verifyGetShareMetadataWorker($res, $metadata);
        } catch (ServiceException $e) {
            if (is_null($options->getTimeout()) || $options->getTimeout() > 0) {
                throw $e;
            }

            $this->assertEquals(TestResources::STATUS_INTERNAL_SERVER_ERROR, $e->getCode(), 'getCode');
        }
        // Clean up.
        $this->restProxy->deleteShare($share);
    }

    private function verifyGetShareMetadataWorker($ret, $metadata): void
    {
        $this->assertNotNull($ret->getMetadata(), 'share Metadata');
        $this->assertNotNull($ret->getETag(), 'share getETag');
        $this->assertNotNull($ret->getLastModified(), 'share getLastModified');

        $this->assertEquals(count($metadata), count($ret->getMetadata()), 'Metadata');
        $md = $ret->getMetadata();
        foreach ($metadata as $key => $value) {
            $this->assertEquals($value, $md[$key], 'Metadata(' . $key . ')');
        }

        // Make sure the last modified date is within 10 seconds
        $now = new DateTime();
        $this->assertTrue(
            FileServiceFunctionalTestData::diffInTotalSeconds(
                $ret->getLastModified(),
                $now
            ) < 10,
            'Last modified date (' .
            $ret->getLastModified()->format(DateTime::RFC1123) .
                ')' . ' should be within 10 seconds of $now (' .
                $now->format(DateTime::RFC1123) . ')'
        );
    }

    public function testSetShareMetadataNoOptions(): void
    {
        $interestingMetadata = FileServiceFunctionalTestData::getInterestingMetadata();
        foreach ($interestingMetadata as $metadata) {
            $this->setShareMetadataWorker(null, $metadata);
        }
    }

    public function testSetShareMetadata(): void
    {
        $interestingSetShareMetadataOptions = FileServiceFunctionalTestData::getFileServiceOptions();
        $interestingMetadata = FileServiceFunctionalTestData::getInterestingMetadata();

        foreach ($interestingSetShareMetadataOptions as $options) {
            foreach ($interestingMetadata as $metadata) {
                $this->setShareMetadataWorker($options, $metadata);
            }
        }
    }

    private function setShareMetadataWorker($options, $metadata): void
    {
        $share = FileServiceFunctionalTestData::getInterestingShareName();

        // Make sure there is something to test
        $this->restProxy->createShare($share);

        $firstkey = '';
        if (!is_null($metadata) && count($metadata) > 0) {
            $firstkey = array_keys($metadata);
            $firstkey = $firstkey[0];
        }

        try {
            // And put in some metadata
            if (is_null($options)) {
                $this->restProxy->setShareMetadata($share, $metadata);
            } else {
                $this->restProxy->setShareMetadata($share, $metadata, $options);
            }

            if (is_null($options)) {
                $options = new FileServiceOptions();
            }

            $this->assertFalse(
                Utilities::startsWith($firstkey, '<'),
                'Should get HTTP request error if the metadata is invalid'
            );

            if (!is_null($options->getTimeout()) && $options->getTimeout() < 1) {
                $this->assertTrue(false, 'Expect negative timeouts in $options to throw');
            }

            $res = $this->restProxy->getShareMetadata($share);
            $this->verifyGetShareMetadataWorker($res, $metadata);
        } catch (ServiceException $e) {
            if (Utilities::startsWith($firstkey, '<')) {
                $this->assertEquals(TestResources::STATUS_BAD_REQUEST, $e->getCode(), 'getCode');
            } elseif (!is_null($options->getTimeout()) && $options->getTimeout() < 1) {
                $this->assertEquals(TestResources::STATUS_INTERNAL_SERVER_ERROR, $e->getCode(), 'getCode');
            } else {
                throw $e;
            }
        }

        // Clean up.
        $this->restProxy->deleteShare($share);
    }

    public function testGetSharePropertiesNoOptions(): void
    {
        $metadata = FileServiceFunctionalTestData::getNiceMetadata();
        $this->getSharePropertiesWorker(null, $metadata);
    }

    public function testGetShareProperties(): void
    {
        $interestingTimeouts = FileServiceFunctionalTestData::getInterestingTimeoutValues();
        $metadata = FileServiceFunctionalTestData::getNiceMetadata();
        foreach ($interestingTimeouts as $timeout) {
            $options = new FileServiceOptions();
            $options->setTimeout($timeout);
            $this->getSharePropertiesWorker($options, $metadata);
        }
    }

    private function getSharePropertiesWorker($options, $metadata): void
    {
        $share = FileServiceFunctionalTestData::getInterestingShareName();

        // Make sure there is something to test
        $this->restProxy->createShare($share);
        $this->restProxy->setShareMetadata($share, $metadata);

        try {
            $res = (is_null($options) ?
                $this->restProxy->getShareProperties($share) :
                $this->restProxy->getShareProperties($share, $options));

            if (is_null($options)) {
                $options = new FileServiceOptions();
            }

            if (!is_null($options->getTimeout()) && $options->getTimeout() < 1) {
                $this->assertTrue(false, 'Expect negative timeouts in $options to throw');
            }

            $this->verifyGetShareMetadataWorker($res, $metadata);
        } catch (ServiceException $e) {
            if (is_null($options->getTimeout()) || $options->getTimeout() >= 1) {
                throw $e;
            }

            $this->assertEquals(TestResources::STATUS_INTERNAL_SERVER_ERROR, $e->getCode(), 'getCode');
        }

        // Clean up.
        $this->restProxy->deleteShare($share);
    }

    public function testGetShareACLNoOptions(): void
    {
        $this->getShareACLWorker(null);
    }

    public function testGetShareACL(): void
    {
        $interestingTimeouts = FileServiceFunctionalTestData::getInterestingTimeoutValues();
        foreach ($interestingTimeouts as $timeout) {
            $options = new FileServiceOptions();
            $options->setTimeout($timeout);
            $this->getShareACLWorker($options);
        }
    }

    private function getShareACLWorker($options): void
    {
        $share = FileServiceFunctionalTestData::getInterestingShareName();

        // Make sure there is something to test
        $this->restProxy->createShare($share);

        try {
            $res = (is_null($options) ?
                $this->restProxy->getShareACL($share) :
                $this->restProxy->getShareACL($share, $options));

            if (is_null($options)) {
                $options = new FileServiceOptions();
            }

            if (!is_null($options->getTimeout()) && $options->getTimeout() < 1) {
                $this->assertTrue(false, 'Expect negative timeouts in $options to throw');
            }

            $this->verifyGetShareACLWorker($res);
        } catch (ServiceException $e) {
            if (is_null($options->getTimeout()) || $options->getTimeout() >= 1) {
                throw $e;
            }

            $this->assertEquals(TestResources::STATUS_INTERNAL_SERVER_ERROR, $e->getCode(), 'getCode');
        }

        // Clean up.
        $this->restProxy->deleteShare($share);
    }

    private function verifyGetShareACLWorker($ret): void
    {
        $this->assertNotNull($ret->getShareACL(), '$ret->getShareACL');
        $this->assertNotNull($ret->getETag(), '$ret->getETag');
        $this->assertNotNull($ret->getLastModified(), '$ret->getLastModified');
        $this->assertNotNull(
            $ret->getShareACL()->getSignedIdentifiers(),
            '$ret->getShareACL->getSignedIdentifiers'
        );

        // Make sure the last modified date is within 10 seconds
        $now = new DateTime();
        $this->assertTrue(
            FileServiceFunctionalTestData::diffInTotalSeconds(
                $ret->getLastModified(),
                $now
            ) < 10000,
            'Last modified date (' .
                $ret->getLastModified()->format(DateTime::RFC1123) .
                ') ' . 'should be within 10 seconds of $now (' .
                $now->format(DateTime::RFC1123) . ')'
        );
    }

    public function testSetShareACLNoOptions(): void
    {
        $interestingACL = FileServiceFunctionalTestData::getInterestingACL();
        foreach ($interestingACL as $acl) {
            $this->setShareACLWorker(null, $acl);
        }
    }

    public function testSetShareACL(): void
    {
        $interestingACL = FileServiceFunctionalTestData::getInterestingACL();
        $interestingTimeouts = FileServiceFunctionalTestData::getInterestingTimeoutValues();
        foreach ($interestingTimeouts as $timeout) {
            foreach ($interestingACL as $acl) {
                $options = new FileServiceOptions();
                $options->setTimeout($timeout);
                $this->setShareACLWorker($options, $acl);
            }
        }
    }

    private function setShareACLWorker($options, $acl): void
    {
        $share = FileServiceFunctionalTestData::getInterestingShareName();

        // Make sure there is something to test
        $this->restProxy->createShare($share);
        $fileContent = uniqid();
        $this->restProxy->createFileFromContent($share, 'test', $fileContent);

        try {
            if (is_null($options)) {
                $this->restProxy->setShareACL($share, $acl);
                $this->restProxy->setShareACL($share, $acl);
            } else {
                $this->restProxy->setShareACL($share, $acl, $options);
                $this->restProxy->setShareACL($share, $acl, $options);
            }

            if (is_null($options)) {
                $options = new FileServiceOptions();
            }

            if (!is_null($options->getTimeout()) && $options->getTimeout() < 1) {
                $this->assertTrue(false, 'Expect negative timeouts in $options to throw');
            }

            $res = $this->restProxy->getShareACL($share);
            $this->verifySetShareACLWorker($res, $share, $acl, $fileContent);
        } catch (ServiceException $e) {
            if (!is_null($options->getTimeout()) && $options->getTimeout() < 1) {
                $this->assertEquals(TestResources::STATUS_INTERNAL_SERVER_ERROR, $e->getCode(), 'getCode');
            } else {
                throw $e;
            }
        }

        // Clean up.
        $this->restProxy->deleteShare($share);
    }

    private function verifySetShareACLWorker($ret, $share, $acl, $fileContent): void
    {
        $this->assertNotNull($ret->getShareACL(), '$ret->getShareACL');
        $this->assertNotNull($ret->getETag(), '$ret->getShareACL->getETag');
        $now = new DateTime();
        $this->assertTrue(
            FileServiceFunctionalTestData::diffInTotalSeconds(
                $ret->getLastModified(),
                $now
            ) < 10000,
            'Last modified date (' .
                $ret->getLastModified()->format(DateTime::RFC1123) .
                ') ' . 'should be within 10 seconds of $now (' .
                $now->format(DateTime::RFC1123) . ')'
        );

        $this->assertNotNull(
            $ret->getShareACL()->getSignedIdentifiers(),
            '$ret->getShareACL->getSignedIdentifiers'
        );

        $expIds = $acl->getSignedIdentifiers();
        $actIds = $ret->getShareACL()->getSignedIdentifiers();
        $this->assertEquals(count($expIds), count($actIds), '$ret->getShareACL->getSignedIdentifiers');

        for ($i = 0; $i < count($expIds); $i++) {
            $expId = $expIds[$i];
            $actId = $actIds[$i];
            $this->assertEquals($expId->getId(), $actId->getId(), 'SignedIdentifiers[' . $i . ']->getId');
            $this->assertEquals(
                $expId->getAccessPolicy()->getPermission(),
                $actId->getAccessPolicy()->getPermission(),
                'SignedIdentifiers[' . $i . ']->getAccessPolicy->getPermission'
            );
            $this->assertTrue(
                FileServiceFunctionalTestData::diffInTotalSeconds(
                    $expId->getAccessPolicy()->getStart(),
                    $actId->getAccessPolicy()->getStart()
                ) < 1,
                'SignedIdentifiers[' . $i . ']->getAccessPolicy->getStart should match within 1 second, ' .
                    'exp=' . $expId->getAccessPolicy()->getStart()->format(DateTime::RFC1123) . ', ' .
                    'act=' . $actId->getAccessPolicy()->getStart()->format(DateTime::RFC1123)
            );
            $this->assertTrue(
                FileServiceFunctionalTestData::diffInTotalSeconds(
                    $expId->getAccessPolicy()->getExpiry(),
                    $actId->getAccessPolicy()->getExpiry()
                ) < 1,
                'SignedIdentifiers[' . $i . ']->getAccessPolicy->getExpiry should match within 1 second, ' .
                    'exp=' . $expId->getAccessPolicy()->getExpiry()->format(DateTime::RFC1123) . ', ' .
                    'act=' . $actId->getAccessPolicy()->getExpiry()->format(DateTime::RFC1123)
            );
        }
    }

    private function prepareDirectoriesAndFiles($shareName, $directoriesCount, $filesCount): void
    {
        for ($i = 0; $i < $directoriesCount; ++$i) {
            $this->restProxy->createDirectory(
                $shareName,
                FileServiceFunctionalTestData::getInterestingDirectoryName()
            );
        }
        for ($i = 0; $i < $filesCount; ++$i) {
            $this->restProxy->createFile(
                $shareName,
                FileServiceFunctionalTestData::getInterestingFileName(),
                rand(1, 100)
            );
        }
    }

    public function testListDirectoriesAndFilesNoOptions(): void
    {
        $share = FileServiceFunctionalTestData::getInterestingShareName();
        $this->safeCreateShare($share);
        $this->prepareDirectoriesAndFiles($share, 5, 5);
        $this->listDirectoriesAndFilesWorker($share, null);
        $this->safeDeleteShare($share);
    }

    public function testListDirectoriesAndFiles(): void
    {
        $interestingListFilesOptions =
            FileServiceFunctionalTestData::getInterestingListDirectoriesAndFilesOptions();
        $share = FileServiceFunctionalTestData::getInterestingShareName();
        $this->safeCreateShare($share);
        $this->prepareDirectoriesAndFiles($share, 10, 10);
        foreach ($interestingListFilesOptions as $options) {
            $this->listDirectoriesAndFilesWorker($share, $options);
        }
        $this->safeDeleteShare($share);
    }

    private function listDirectoriesAndFilesWorker($share, $options): void
    {
        $finished = false;
        while (!$finished) {
            try {
                $ret = (is_null($options) ?
                    $this->restProxy->listDirectoriesAndFiles($share) :
                    $this->restProxy->listDirectoriesAndFiles($share, '', $options));

                if (is_null($options)) {
                    $options = new ListDirectoriesAndFilesOptions();
                }

                if (!is_null($options->getTimeout()) && $options->getTimeout() < 1) {
                    $this->assertTrue(false, 'Expect negative timeouts in $options to throw');
                }
                $this->verifyListDirectoriesAndFilesWorker($ret, $options);

                if (strlen($ret->getNextMarker()) == 0) {
                    $finished = true;
                } else {
                    $options->setMarker($ret->getNextMarker());
                }
            } catch (ServiceException $e) {
                $finished = true;
                if (is_null($options->getTimeout()) || $options->getTimeout() >= 1) {
                    throw $e;
                }

                $this->assertEquals(TestResources::STATUS_INTERNAL_SERVER_ERROR, $e->getCode(), 'getCode');
            }
        }
    }

    private function verifyListDirectoriesAndFilesWorker($ret, $options): void
    {
        $this->assertEquals($options->getMaxResults(), $ret->getMaxResults(), 'getMaxResults');

        $this->assertNotNull($ret->getFiles(), 'getFiles');
        if ($options->getMaxResults() == 0) {
            $this->assertEquals(
                0,
                strlen($ret->getNextMarker()),
                'When MaxResults is 0, expect getNextMarker (' .
                    strlen($ret->getNextMarker()) . ')to be  '
            );
        } elseif (strlen($ret->getNextMarker()) == 0) {
            $this->assertTrue(
                count($ret->getFiles()) + count($ret->getDirectories()) <= $options->getMaxResults(),
                'when NextMarker (\'' . $ret->getNextMarker() .
                '\')==\'\', Files length (' .
                count($ret->getFiles()) + count($ret->getDirectories()) .
                    ') should be <= MaxResults (' .
                    $options->getMaxResults() . ')'
            );
        } else {
            $this->assertEquals(
                $options->getMaxResults(),
                count($ret->getFiles()) + count($ret->getDirectories()),
                'when NextMarker (' . $ret->getNextMarker() .
                    ')!=\'\', Files length (' .
                    count($ret->getFiles()) + count($ret->getDirectories()) .
                    ') should be == MaxResults (' .
                    $options->getMaxResults() . ')'
            );
        }
    }

    public function testGetFileMetadataNoOptions(): void
    {
        $share = FileServiceFunctionalTestData::getInterestingShareName();
        $this->safeCreateShare($share);
        $this->getFileMetadataWorker($share, null);
        $this->safeDeleteShare($share);
    }

    public function testGetFileMetadata(): void
    {
        $interestingTimeouts = FileServiceFunctionalTestData::getInterestingTimeoutValues();

        foreach ($interestingTimeouts as $timeout) {
            $share = FileServiceFunctionalTestData::getInterestingShareName();
            $this->safeCreateShare($share);
            $options = new FileServiceOptions();
            $options->setTimeout($timeout);
            $this->getFileMetadataWorker($share, $options);
            $this->safeDeleteShare($share);
        }
    }

    private function getFileMetadataWorker($share, $options): void
    {
        $file = FileServiceFunctionalTestData::getInterestingFileName($share);

        // Make sure there is something to test
        $testContent = uniqid();
        $this->restProxy->createFileFromContent($share, $file, $testContent);

        $properties = FileServiceFunctionalTestData::getNiceMetadata();
        $this->restProxy->setFileMetadata($share, $file, $properties);

        try {
            $res = (is_null($options) ?
                $this->restProxy->getFileMetadata($share, $file) :
                $this->restProxy->getFileMetadata($share, $file, $options)
            );

            if (is_null($options)) {
                $options = new FileServiceOptions();
            }

            if (!is_null($options->getTimeout()) && ($options->getTimeout() < 1 || $options->getTimeout() == 2147483647)) {
                $this->assertTrue(false, 'Expect negative timeouts in $options to throw');
            }
            $this->verifyGetFileMetadataWorker($res, $properties);
        } catch (ServiceException $e) {
            if (!is_null($options->getTimeout()) && ($options->getTimeout() < 1 || $options->getTimeout() == 2147483647)) {
                $this->assertEquals(TestResources::STATUS_INTERNAL_SERVER_ERROR, $e->getCode(), 'bad timeout: getCode');
            } else {
                throw $e;
            }
        }
    }

    private function verifyGetFileMetadataWorker($res, $metadata): void
    {
        $this->assertNotNull($res->getMetadata(), 'file Metadata');
        $this->assertNotNull($res->getETag(), 'file getETag');
        $this->assertNotNull($res->getLastModified(), 'file getLastModified');

        $this->assertEquals(count($metadata), count($res->getMetadata()), 'Metadata');
        $retMetadata = $res->getMetadata();
        foreach ($metadata as $key => $value) {
            $this->assertEquals($value, $retMetadata[$key], 'Metadata(' . $key . ')');
        }

        // Make sure the last modified date is within 10 seconds
        $now = new DateTime();
        $this->assertTrue(
            FileServiceFunctionalTestData::diffInTotalSeconds(
                $res->getLastModified(),
                $now
            ) < 10000,
            'Last modified date (' .
                $res->getLastModified()->format(DateTime::RFC1123) .
                ') ' . 'should be within 10 seconds of $now (' .
                $now->format(DateTime::RFC1123) . ')'
        );
    }

    public function testSetFileMetadataNoOptions(): void
    {
        $interestingMetadata = FileServiceFunctionalTestData::getInterestingMetadata();
        foreach ($interestingMetadata as $properties) {
            $share = FileServiceFunctionalTestData::getInterestingShareName();
            $this->safeCreateShare($share);
            $this->setFileMetadataWorker($share, null, $properties);
            $this->safeDeleteShare($share);
        }
    }

    public function testSetFileMetadata(): void
    {
        $interestingSetFileMetadataOptions = FileServiceFunctionalTestData::getFileServiceOptions();
        $interestingMetadata = FileServiceFunctionalTestData::getInterestingMetadata();

        foreach ($interestingSetFileMetadataOptions as $options) {
            foreach ($interestingMetadata as $properties) {
                $share = FileServiceFunctionalTestData::getInterestingShareName();
                $this->safeCreateShare($share);
                $this->setFileMetadataWorker($share, $options, $properties);
                $this->safeDeleteShare($share);
            }
        }
    }

    private function setFileMetadataWorker($share, $options, $metadata): void
    {
        $file = FileServiceFunctionalTestData::getInterestingFileName($share);

        // Make sure there is something to test
        $testContent = uniqid();
        $this->restProxy->createFileFromContent($share, $file, $testContent);

        $firstkey = '';
        if (!is_null($metadata) && count($metadata) > 0) {
            $firstkey = array_keys($metadata);
            $firstkey = $firstkey[0];
        }

        try {
            // And put in some properties
            (is_null($options) ?
            $this->restProxy->setFileMetadata($share, $file, $metadata) :
            $this->restProxy->setFileMetadata(
                $share,
                $file,
                $metadata,
                $options
            ));
            if (is_null($options)) {
                $options = new FileServiceOptions();
            }

            if (!is_null($options->getTimeout()) && $options->getTimeout() < 1) {
                $this->assertTrue(false, 'Expect negative timeouts in $options to throw');
            }
            if (Utilities::startsWith($firstkey, '<')) {
                $this->assertTrue(false, 'Should get HTTP request error if the metadata is invalid');
            }

            $res = $this->restProxy->getFileMetadata($share, $file);
            $this->verifyGetFileMetadataWorker($res, $metadata);
        } catch (ServiceException $e) {
            if (Utilities::startsWith($firstkey, '<')) {
                $this->assertEquals(TestResources::STATUS_BAD_REQUEST, $e->getCode(), 'getCode');
            } elseif (!is_null($options->getTimeout()) && $options->getTimeout() < 1) {
                $this->assertEquals(TestResources::STATUS_INTERNAL_SERVER_ERROR, $e->getCode(), 'bad timeout: getCode');
            } else {
                throw $e;
            }
        }
    }

    private function verifySetFileMetadataWorker($res): void
    {
        $this->assertNotNull($res->getETag(), 'file getETag');
        $this->assertNotNull($res->getLastModified(), 'file getLastModified');

        // Make sure the last modified date is within 10 seconds
        $now = new DateTime();
        $this->assertTrue(
            FileServiceFunctionalTestData::diffInTotalSeconds(
                $res->getLastModified(),
                $now
            ) < 10000,
            'Last modified date (' .
            $res->getLastModified()->format(DateTime::RFC1123) . ') ' .
                'should be within 10 seconds of $now (' .
                $now->format(DateTime::RFC1123) . ')'
        );
    }

    public function testGetSetFileProperties(): void
    {
        $interestingFileProperties =
            FileServiceFunctionalTestData::getSetFileProperties();

        foreach ($interestingFileProperties as $properties) {
            $share = FileServiceFunctionalTestData::getInterestingShareName();
            $this->safeCreateShare($share);
            $this->getSetFilePropertiesWorker($share, $properties);
            $this->safeDeleteShare($share);
        }
    }

    private function getSetFilePropertiesWorker($share, $properties): void
    {
        $file = FileServiceFunctionalTestData::getInterestingFileName($share);

        // Make sure there is something to test
        $testContent = uniqid();
        $this->restProxy->createFileFromContent($share, $file, $testContent);

        if ($properties->getContentLength() == null) {
            $properties->setContentLength(strlen($testContent));
        }
        if ($properties->getContentType() == null) {
            $properties->setContentType('application/x-www-form-urlencoded');
        }
        $this->restProxy->setFileProperties($share, $file, $properties);

        $res = $this->restProxy->getFileProperties($share, $file);

        $this->verifyGetSetFilePropertiesWorker($res, $properties);
    }

    private function verifyGetSetFilePropertiesWorker($res, $properties): void
    {
        $this->assertEquals(
            $res->getContentLength(),
            $properties->getContentLength()
        );
        $this->assertEquals(
            $res->getContentType(),
            $properties->getContentType()
        );
        $this->assertEquals(
            $res->getContentMD5(),
            $properties->getContentMD5()
        );
        $this->assertEquals(
            $res->getContentEncoding(),
            $properties->getContentEncoding()
        );
        $this->assertEquals(
            $res->getContentLanguage(),
            $properties->getContentLanguage()
        );
        $this->assertEquals(
            $res->getCacheControl(),
            $properties->getCacheControl()
        );
        $this->assertEquals(
            $res->getContentDisposition(),
            $properties->getContentDisposition()
        );
        $this->assertNotNull($res->getETag());

        // Make sure the last modified date is within 10 seconds
        $now = new DateTime();
        $this->assertTrue(
            FileServiceFunctionalTestData::diffInTotalSeconds(
                $res->getLastModified(),
                $now
            ) < 10000,
            'Last modified date (' .
                $res->getLastModified()->format(
                    DateTime::RFC1123
                ) . ') ' .
                    'should be within 10 seconds of $now (' .
                    $now->format(DateTime::RFC1123) . ')'
        );
    }

    public function testGetFileNoOptions(): void
    {
        $share = FileServiceFunctionalTestData::getInterestingShareName();
        $this->safeCreateShare($share);
        $this->getFileWorker(null, $share);
        $this->safeDeleteShare($share);
    }

    public function testGetFileAllOptions(): void
    {
        $interestingGetFileOptions = FileServiceFunctionalTestData::getGetFileOptions();
        foreach ($interestingGetFileOptions as $options) {
            $share = FileServiceFunctionalTestData::getInterestingShareName();
            $this->safeCreateShare($share);
            $this->getFileWorker($options, $share);
            $this->safeDeleteShare($share);
        }
    }

    private function getFileWorker($options, $share): void
    {
        $file = FileServiceFunctionalTestData::getInterestingFileName();

        // Make sure there is something to test
        $dataSize = 512;
        $content = FileServiceFunctionalTestData::getRandomBytes($dataSize);
        $createFileOptions = new createFileFromContentOptions();
        $createFileOptions->setUseTransactionalMD5(true);
        if ($options && $options->getRangeGetContentMD5()) {
            $createFileOptions->setContentMD5('MDAwMDAwMDA=');
        }
        $this->restProxy->createFileFromContent($share, $file, $content, $createFileOptions);

        $metadata = FileServiceFunctionalTestData::getNiceMetadata();
        $sbmd = $this->restProxy->setFileMetadata($share, $file, $metadata);

        try {
            $res = (is_null($options) ?
                $this->restProxy->getFile($share, $file) :
                $this->restProxy->getFile($share, $file, $options)
            );

            if (is_null($options)) {
                $options = new GetFileOptions();
            }

            if (!is_null($options->getTimeout()) && $options->getTimeout() < 1) {
                $this->assertTrue(false, 'Expect negative timeouts in $options to throw');
            }
            if ($options->getRangeGetContentMD5() && is_null($options->getRangeString())) {
                $this->assertTrue(false, 'Expect compute range MD5 to fail if range not set');
            }

            $this->verifyGetFileWorker($res, $options, $dataSize, $metadata);
        } catch (ServiceException $e) {
            if ($options->getRangeGetContentMD5() && is_null($options->getRangeString())) {
                $this->assertEquals(
                    TestResources::STATUS_BAD_REQUEST,
                    $e->getCode(),
                    'Expect compute range MD5 to fail when range not set:' .
                    ' getCode'
                );
            } elseif (!is_null($options->getTimeout()) && $options->getTimeout() < 1) {
                $this->assertEquals(
                    TestResources::STATUS_INTERNAL_SERVER_ERROR,
                    $e->getCode(),
                    'bad timeout: getCode'
                );
            } else {
                throw $e;
            }
        }
    }

    private function verifyGetFileWorker($res, $options, $dataSize, $metadata): void
    {
        $this->assertNotNull($res, 'result');

        $content =  stream_get_contents($res->getContentStream());

        $range = $options->getRange();
        if ($range == null) {
            $range = new Range(0);
        }
        $rangeSize = $range->getLength();
        if ($rangeSize == null) {
            $rangeSize = $dataSize - $range->getStart();
        }

        $this->assertEquals(
            $rangeSize,
            strlen($content),
            '$content length and range'
        );

        if ($options->getRangeGetContentMD5()) {
            $this->assertEquals(
                'MDAwMDAwMDA=',
                $res->getProperties()->getContentMD5(),
                'asked for MD5, result->getProperties()->getContentMD5'
            );
        } else {
            $this->assertNull(
                $res->getProperties()->getContentMD5(),
                'did not ask for MD5, result->getProperties()->getContentMD5'
            );
        }

        $this->assertNotNull($res->getMetadata(), 'file Metadata');
        $resMetadata = $res->getMetadata();
        $this->assertEquals(count($metadata), count($resMetadata), 'Metadata');
        foreach ($metadata as $key => $value) {
            $this->assertEquals($value, $resMetadata[$key], 'Metadata(' . $key . ')');
        }
    }

    public function testDeleteFileNoOptions(): void
    {
        $share = FileServiceFunctionalTestData::getInterestingShareName();
        $this->safeCreateShare($share);
        $this->deleteFileWorker(null, $share);
        $this->safeDeleteShare($share);
    }

    public function testDeleteFile(): void
    {
        $interestingDeleteFileOptions = FileServiceFunctionalTestData::getFileServiceOptions();
        foreach ($interestingDeleteFileOptions as $options) {
            $share = FileServiceFunctionalTestData::getInterestingShareName();
            $this->safeCreateShare($share);
            $this->deleteFileWorker($options, $share);
            $this->safeDeleteShare($share);
        }
    }

    private function deleteFileWorker($options, $share): void
    {
        $file = FileServiceFunctionalTestData::getInterestingFileName($share);

        // Make sure there is something to test
        $dataSize = 512;
        $content = FileServiceFunctionalTestData::getRandomBytes($dataSize);
        $this->restProxy->createFileFromContent($share, $file, $content);

        try {
            if (is_null($options)) {
                $this->restProxy->deleteFile($share, $file);
            } else {
                $this->restProxy->deleteFile($share, $file, $options);
            }
            $deleted = true;

            if (is_null($options)) {
                $options = new FileServiceOptions();
            }

            if (!is_null($options->getTimeout()) && $options->getTimeout() < 1) {
                $this->assertTrue(false, 'Expect negative timeouts in $options to throw');
            }

            $listDirectoriesAndFilesResult =
                $this->restProxy->listDirectoriesAndFiles($share);

            $files = $listDirectoriesAndFilesResult->getFiles();

            $this->assertEquals(0, count($files), 'File should be deleted');
        } catch (ServiceException $e) {
            if (!is_null($options->getTimeout()) && $options->getTimeout() < 1) {
                $this->assertEquals(
                    TestResources::STATUS_INTERNAL_SERVER_ERROR,
                    $e->getCode(),
                    'bad timeout: deleteHttpStatusCode'
                );
            } else {
                throw $e;
            }
        }
    }

    public function testCopyFileNoOptions(): void
    {
        $sourceShares = FileServiceFunctionalTestData::$testShareNames;

        $destShares = FileServiceFunctionalTestData::$testShareNames;

        foreach ($sourceShares as $sourceShare) {
            foreach ($destShares as $destShare) {
                $this->copyFileWorker(null, null, $sourceShare, $destShare);
            }
        }
    }

    public function testCopyFile(): void
    {
        $sourceShare = FileServiceFunctionalTestData::$testShareNames[0];
        $destShare = FileServiceFunctionalTestData::$testShareNames[1];

        $pairs = FileServiceFunctionalTestData::getCopyFileMetaOptionsPairs();
        foreach ($pairs as $pair) {
            $this->copyFileWorker(
                $pair['metadata'],
                $pair['options'],
                $sourceShare,
                $destShare
            );
        }
    }

    private function copyFileWorker($metadata, $options, $sourceShare, $destShare): void
    {
        $sourceFile = FileServiceFunctionalTestData::getInterestingFileName();
        $destFile = FileServiceFunctionalTestData::getInterestingFileName();

        // Make sure there is something to test
        $sourceDataSize = 512;
        $content = FileServiceFunctionalTestData::getRandomBytes($sourceDataSize);
        $this->restProxy->createFileFromContent($sourceShare, $sourceFile, $content);

        $destDataSize = 2048;
        $this->restProxy->createFile($destShare, $destFile, $destDataSize);

        $sourceMeta = FileServiceFunctionalTestData::getNiceMetadata();
        $this->restProxy->setFileMetadata($sourceShare, $sourceFile, $sourceMeta);

        $sourcePath = sprintf(
            '%s%s/%s',
            (string) $this->restProxy->getPsrPrimaryUri(),
            $sourceShare,
            $sourceFile
        );

        try {
            if (is_null($metadata)) {
                $this->restProxy->copyFile(
                    $destShare,
                    $destFile,
                    $sourcePath,
                    [],
                    $options
                );
            } else {
                $this->restProxy->copyFile(
                    $destShare,
                    $destFile,
                    $sourcePath,
                    $metadata,
                    $options
                );
            }

            if (is_null($options)) {
                $options = new FileServiceOptions();
            }

            if (!is_null($options->getTimeout()) && $options->getTimeout() < 1) {
                $this->assertTrue(false, 'Expect negative timeouts in $options to throw');
            }
            $listDirectoriesAndFilesResult =
                $this->restProxy->listDirectoriesAndFiles($destShare);
            $files = $listDirectoriesAndFilesResult->getFiles();

            $getFileResult = $this->restProxy->getFile($destShare, $destFile);

            $this->verifyCopyFileWorker(
                $sourceShare,
                $destShare,
                $options,
                $files,
                $getFileResult,
                $sourceDataSize,
                $metadata,
                $sourceMeta,
                $content
            );
        } catch (ServiceException $e) {
            if (is_null($options)) {
                $options = new CopyFileOptions();
            }

            if (!is_null($options->getTimeout()) && $options->getTimeout() < 1) {
                $this->assertEquals(500, $e->getCode(), 'bad timeout: deleteHttpStatusCode');
            } else {
                throw $e;
            }
        }

        // Clean up.
        $this->restProxy->deleteFile($sourceShare, $sourceFile);
        $this->restProxy->deleteFile($destShare, $destFile);
    }

    private function verifyCopyFileWorker(
        $sourceShare,
        $destShare,
        $options,
        $files,
        $getFileResult,
        $sourceDataSize,
        $metadata,
        $sourceMeta,
        $sourceContent
    ): void {
        $this->assertEquals(
            $sourceShare == $destShare ? 2 : 1,
            count($files)
        );
        $this->assertEquals(
            $sourceDataSize,
            $getFileResult->getProperties()->getContentLength(),
            'Dest length should be the same as the source length'
        );

        $this->assertNotNull($getFileResult->getMetadata(), 'file Metadata');
        $expectedMetadata = $metadata == null ? $sourceMeta : $metadata;
        $resMetadata = $getFileResult->getMetadata();
        $this->assertEquals(
            count($expectedMetadata),
            count($resMetadata),
            'Metadata'
        );
        foreach ($expectedMetadata as $key => $value) {
            $this->assertEquals(
                $value,
                $resMetadata[strtolower($key)],
                'Metadata(' . $key . ')'
            );
        }
        $resContent = stream_get_contents($getFileResult->getContentStream());
        $this->assertEquals($sourceContent, $resContent);

        // Make sure the last modified date is within 10 seconds
        $now = new DateTime();
        $this->assertTrue(
            FileServiceFunctionalTestData::diffInTotalSeconds(
                $getFileResult->getProperties()->getLastModified(),
                $now
            ) < 10,
            'Last modified date (' .
                $getFileResult->getProperties()->getLastModified()->format(
                    DateTime::RFC1123
                ) . ')' . ' should be within 10 seconds of $now (' .
                $now->format(DateTime::RFC1123) . ')'
        );
    }

    public function testPutListClearRanges(): void
    {
        $rangesArray = FileServiceFunctionalTestData::getRangesArray();
        $share = FileServiceFunctionalTestData::getInterestingShareName();
        $this->safeCreateShare($share);
        $file = FileServiceFunctionalTestData::getInterestingFileName();
        $this->restProxy->createFile($share, $file, 2048);
        foreach ($rangesArray as $array) {
            $this->putListClearRangesWorker(
                $share,
                $file,
                $array['putRange'],
                $array['clearRange'],
                $array['listRange'],
                $array['resultListRange']
            );
        }
        $this->safeDeleteShare($share);
    }

    public function testCreateFileWithNameIsZero(): void
    {
        $share = FileServiceFunctionalTestData::getInterestingShareName();
        $this->safeCreateShare($share);
        $file = '0';
        $this->restProxy->createFile($share, $file, 2048);
        $res = $this->restProxy->getFileProperties($share, $file);
        $this->assertEquals(2048, $res->getContentLength());
        $this->safeDeleteShare($share);
    }

    private function putListClearRangesWorker(
        $share,
        $file,
        $putRange,
        $clearRange,
        $listRange,
        $resultListRange
    ): void {
        if ($putRange != null) {
            $length = $putRange->getLength();
            if ($length == null) {
                $length = 2048 - $putRange->getStart();
            }
            $content = FileServiceFunctionalTestData::getRandomBytes($length);
            $options = new PutFileRangeOptions();
            //setting the wrong md5.
            $options->setContentMD5(Utilities::calculateContentMD5(''));
            $message = '';
            try {
                $this->restProxy->putFileRange($share, $file, $content, $putRange, $options);
            } catch (ServiceException $e) {
                $message = $e->getMessage();
            }
            $this->assertContains('400', $message);
            $this->assertContains(
                'The MD5 value specified in the request did not match with the MD5 value calculated by the server.',
                $message
            );
            //Ends debug code snippet
            // Now set the correct content MD5
            $options->setContentMD5(Utilities::calculateContentMD5($content));
            $this->restProxy->putFileRange($share, $file, $content, $putRange, $options);
            $getOptions = new GetFileOptions();
            $getOptions->setRange($putRange);
            $getOptions->setRangeGetContentMD5(true);
            $result = $this->restProxy->getFile($share, $file, $getOptions);
            $actualContent = stream_get_contents($result->getContentStream());
            $actualMD5 = $result->getProperties()->getRangeContentMD5();
            //Validate
            $this->assertEquals(Utilities::calculateContentMD5($content), $actualMD5);
            $this->assertEquals($content, $actualContent);
        }
        if ($clearRange != null) {
            $this->restProxy->clearFileRange($share, $file, $clearRange);
        }
        //Validate result
        $listResult = $this->restProxy->listFileRange($share, $file, $listRange);
        $this->assertEquals(2048, $listResult->getContentLength());
        $resultRanges = $listResult->getRanges();
        for ($i = 0; $i < count($resultRanges); ++$i) {
            $this->assertEquals($resultListRange[$i], $resultRanges[$i]);
        }
    }

    public function testDirectoriesLogic(): void
    {
        $commands = FileServiceFunctionalTestData::getDirectoriesAndFilesToCreateOrDelete();
        $share = FileServiceFunctionalTestData::getInterestingShareName();
        $this->safeCreateShare($share);
        foreach ($commands as $command) {
            $this->directoriesLogicWorker(
                $share,
                $command['operation'],
                $command['type'],
                $command['path'],
                $command['error']
            );
        }
        $this->safeDeleteShare($share);
    }

    private function directoriesLogicWorker(
        $share,
        $operation,
        $type,
        $path,
        $error
    ): void {
        $worker = null;
        $proxy = $this->restProxy;
        if ($type == 'dir') {
            if ($operation == 'create') {
                $worker = function () use ($share, $path, $proxy): void {
                    $proxy->createDirectory($share, $path);
                };
            } elseif ($operation == 'delete') {
                $worker = function () use ($share, $path, $proxy): void {
                    $proxy->deleteDirectory($share, $path);
                };
            }
        } elseif ($type == 'file') {
            if ($operation == 'create') {
                $worker = function () use ($share, $path, $proxy): void {
                    $proxy->createFile($share, $path, 2048);
                };
            } elseif ($operation == 'delete') {
                $worker = function () use ($share, $path, $proxy): void {
                    $proxy->deleteFile($share, $path);
                };
            }
        }
        $this->assertNotNull($worker);
        $message = '';
        try {
            $worker();
        } catch (ServiceException $e) {
            $message = $e->getMessage();
        }
        if ($error == '') {
            $this->assertEquals($error, $message);
        } else {
            $this->assertContains($error, $message);
        }
    }

    public function testSetDirectoryMetadataNoOptions(): void
    {
        $interestingMetadata = FileServiceFunctionalTestData::getInterestingMetadata();
        foreach ($interestingMetadata as $meta) {
            $share = FileServiceFunctionalTestData::getInterestingShareName();
            $this->safeCreateShare($share);
            $this->setDirectoryMetadataWorker($share, null, $meta);
            $this->safeDeleteShare($share);
        }
    }

    public function testSetDirectoryMetadata(): void
    {
        $interestingSetFileMetadataOptions = FileServiceFunctionalTestData::getFileServiceOptions();
        $interestingMetadata = FileServiceFunctionalTestData::getInterestingMetadata();

        foreach ($interestingSetFileMetadataOptions as $options) {
            foreach ($interestingMetadata as $meta) {
                $share = FileServiceFunctionalTestData::getInterestingShareName();
                $this->safeCreateShare($share);
                $this->setDirectoryMetadataWorker($share, $options, $meta);
                $this->safeDeleteShare($share);
            }
        }
    }

    private function setDirectoryMetadataWorker($share, $options, $metadata): void
    {
        $dir = FileServiceFunctionalTestData::getInterestingDirectoryName();

        // Make sure there is something to test
        $this->restProxy->createDirectory($share, $dir);

        $firstkey = '';
        if (!is_null($metadata) && count($metadata) > 0) {
            $firstkey = array_keys($metadata);
            $firstkey = $firstkey[0];
        }

        try {
            // And put in some properties
            (is_null($options) ?
            $this->restProxy->setDirectoryMetadata($share, $dir, $metadata) :
            $this->restProxy->setDirectoryMetadata(
                $share,
                $dir,
                $metadata,
                $options
            ));
            if (is_null($options)) {
                $options = new FileServiceOptions();
            }

            if (!is_null($options->getTimeout()) && $options->getTimeout() < 1) {
                $this->assertTrue(false, 'Expect negative timeouts in $options to throw');
            }
            if (Utilities::startsWith($firstkey, '<')) {
                $this->assertTrue(false, 'Should get HTTP request error if the metadata is invalid');
            }

            $res = $this->restProxy->getDirectoryMetadata($share, $dir);
            $this->verifyGetDirectoryMetadataWorker($res, $metadata);
        } catch (ServiceException $e) {
            if (Utilities::startsWith($firstkey, '<')) {
                $this->assertEquals(TestResources::STATUS_BAD_REQUEST, $e->getCode(), 'getCode');
            } elseif (!is_null($options->getTimeout()) && $options->getTimeout() < 1) {
                $this->assertEquals(TestResources::STATUS_INTERNAL_SERVER_ERROR, $e->getCode(), 'bad timeout: getCode');
            } else {
                throw $e;
            }
        }
    }

    private function verifyGetDirectoryMetadataWorker($res): void
    {
        $this->assertNotNull($res->getETag(), 'directory getETag');
        $this->assertNotNull($res->getLastModified(), 'directory getLastModified');

        // Make sure the last modified date is within 10 seconds
        $now = new DateTime();
        $this->assertTrue(
            FileServiceFunctionalTestData::diffInTotalSeconds(
                $res->getLastModified(),
                $now
            ) < 10000,
            'Last modified date (' .
            $res->getLastModified()->format(DateTime::RFC1123) . ') ' .
                'should be within 10 seconds of $now (' .
                $now->format(DateTime::RFC1123) . ')'
        );
    }

    public function testMiddlewares(): void
    {
        //setup middlewares.
        $historyMiddleware = new HistoryMiddleware();
        $retryMiddleware = RetryMiddlewareFactory::create(
            RetryMiddlewareFactory::GENERAL_RETRY_TYPE,
            3,
            1
        );

        //setup options for the first try.
        $options = new ListSharesOptions();
        $options->setMiddlewares([$historyMiddleware]);
        //get the response of the server.
        $result = $this->restProxy->listShares($options);
        $response = $historyMiddleware->getHistory()[0]['response'];
        $request = $historyMiddleware->getHistory()[0]['request'];

        //setup the mock handler
        $mock = MockHandler::createWithMiddleware([
            new RequestException(
                'mock 408 exception',
                $request,
                new Response(408, ['test_header' => 'test_header_value'])
            ),
            new Response(500, ['test_header' => 'test_header_value']),
            $response,
        ]);
        $restOptions = ['http' => ['handler' => $mock]];
        $mockProxy = FileRestProxy::createFileService($this->connectionString, $restOptions);

        //test using mock handler.
        $options = new ListSharesOptions();
        $options->setMiddlewares([$retryMiddleware, $historyMiddleware]);
        $newResult = $mockProxy->listShares($options);
        $this->assertTrue(
            $result == $newResult,
            'Mock result does not match server behavior'
        );
        $this->assertTrue(
            $historyMiddleware->getHistory()[1]['reason']->getMessage() == 'mock 408 exception',
            'Mock handler does not gave the first 408 exception correctly'
        );
        $this->assertTrue(
            $historyMiddleware->getHistory()[2]['reason']->getCode() == 500,
            'Mock handler does not gave the second 500 response correctly'
        );
    }

}
