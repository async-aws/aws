<?php

namespace AsyncAws\S3\Tests\Unit\Result;

use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\S3\Result\HeadObjectOutput;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

class HeadObjectOutputTest extends TestCase
{
    public function testHeadObjectOutput(): void
    {
        self::markTestIncomplete('Not implemented');

        $response = new SimpleMockedResponse('<?xml version="1.0" encoding="UTF-8"?>
            <ChangeIt/>
        ');

        $result = new HeadObjectOutput($response, new MockHttpClient());

        self::assertFalse($result->getDeleteMarker());
        self::assertStringContainsString('change it', $result->getAcceptRanges());
        self::assertStringContainsString('change it', $result->getExpiration());
        self::assertStringContainsString('change it', $result->getRestore());
        // self::assertTODO(expected, $result->getLastModified());
        self::assertSame(1337, $result->getContentLength());
        self::assertStringContainsString('change it', $result->getETag());
        self::assertSame(1337, $result->getMissingMeta());
        self::assertStringContainsString('change it', $result->getVersionId());
        self::assertStringContainsString('change it', $result->getCacheControl());
        self::assertStringContainsString('change it', $result->getContentDisposition());
        self::assertStringContainsString('change it', $result->getContentEncoding());
        self::assertStringContainsString('change it', $result->getContentLanguage());
        self::assertStringContainsString('change it', $result->getContentType());
        // self::assertTODO(expected, $result->getExpires());
        self::assertStringContainsString('change it', $result->getWebsiteRedirectLocation());
        self::assertStringContainsString('change it', $result->getServerSideEncryption());
        // self::assertTODO(expected, $result->getMetadata());
        self::assertStringContainsString('change it', $result->getSSECustomerAlgorithm());
        self::assertStringContainsString('change it', $result->getSSECustomerKeyMD5());
        self::assertStringContainsString('change it', $result->getSSEKMSKeyId());
        self::assertStringContainsString('change it', $result->getStorageClass());
        self::assertStringContainsString('change it', $result->getRequestCharged());
        self::assertStringContainsString('change it', $result->getReplicationStatus());
        self::assertSame(1337, $result->getPartsCount());
        self::assertStringContainsString('change it', $result->getObjectLockMode());
        // self::assertTODO(expected, $result->getObjectLockRetainUntilDate());
        self::assertStringContainsString('change it', $result->getObjectLockLegalHoldStatus());
    }
}
