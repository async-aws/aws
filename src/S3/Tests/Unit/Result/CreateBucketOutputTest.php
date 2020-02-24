<?php

namespace AsyncAws\S3\Tests\Unit\Result;

use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\S3\Result\CreateBucketOutput;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

class CreateBucketOutputTest extends TestCase
{
    public function testCreateBucketOutput(): void
    {
        self::markTestIncomplete('Not implemented');

        $response = new SimpleMockedResponse('<?xml version="1.0" encoding="UTF-8"?>
            <ChangeIt/>
        ');

        $client = new MockHttpClient($response);
        $result = new CreateBucketOutput($client->request('POST', 'http://localhost'), $client);

        self::assertStringContainsString('change it', $result->getLocation());
    }
}
