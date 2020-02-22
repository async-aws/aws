<?php

namespace AsyncAws\S3\Tests\Unit\Result;

use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\S3\Result\PutObjectAclOutput;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

class PutObjectAclOutputTest extends TestCase
{
    public function testPutObjectAclOutput(): void
    {
        self::markTestIncomplete('Not implemented');

        $response = new SimpleMockedResponse('<?xml version="1.0" encoding="UTF-8"?>
            <ChangeIt/>
        ');

        $result = new PutObjectAclOutput($response, new MockHttpClient());

        self::assertStringContainsString('change it', $result->getRequestCharged());
    }
}
