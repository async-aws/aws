<?php

namespace AsyncAws\S3\Tests\Unit\Result;

use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\S3\Result\DeleteObjectOutput;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

class DeleteObjectOutputTest extends TestCase
{
    public function testDeleteObjectOutput(): void
    {
        self::markTestIncomplete('Not implemented');

        $response = new SimpleMockedResponse('<?xml version="1.0" encoding="UTF-8"?>
            <ChangeIt/>
        ');

        $client = new MockHttpClient($response);
        $result = new DeleteObjectOutput($client->request('POST', 'http://localhost'), $client);

        self::assertFalse($result->getDeleteMarker());
        self::assertStringContainsString('change it', $result->getVersionId());
        self::assertStringContainsString('change it', $result->getRequestCharged());
    }
}
