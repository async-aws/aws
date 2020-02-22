<?php

namespace AsyncAws\S3\Tests\Unit\Result;

use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\S3\Result\PutObjectOutput;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

class PutObjectOutputTest extends TestCase
{
    public function testPutObjectOutput(): void
    {
        self::markTestIncomplete('Not implemented');

        $response = new SimpleMockedResponse('<?xml version="1.0" encoding="UTF-8"?>
            <ChangeIt/>
        ');

        $result = new PutObjectOutput($response, new MockHttpClient());

        self::assertStringContainsString('change it', $result->getExpiration());
        self::assertStringContainsString('change it', $result->getETag());
        self::assertStringContainsString('change it', $result->getServerSideEncryption());
        self::assertStringContainsString('change it', $result->getVersionId());
        self::assertStringContainsString('change it', $result->getSSECustomerAlgorithm());
        self::assertStringContainsString('change it', $result->getSSECustomerKeyMD5());
        self::assertStringContainsString('change it', $result->getSSEKMSKeyId());
        self::assertStringContainsString('change it', $result->getSSEKMSEncryptionContext());
        self::assertStringContainsString('change it', $result->getRequestCharged());
    }
}
