<?php

namespace AsyncAws\S3\Tests\Unit\Result;

use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Result\PutObjectAclOutput;
use Symfony\Component\HttpClient\MockHttpClient;

class PutObjectAclOutputTest extends TestCase
{
    public function testPutObjectAclOutput(): void
    {
        self::markTestIncomplete('Not implemented');

        // see https://docs.aws.amazon.com/SERVICE/latest/APIReference/API_METHOD.html
        $response = new SimpleMockedResponse('<?xml version="1.0"?>
        <root/>
        ');

        $client = new MockHttpClient($response);
        $result = new PutObjectAclOutput($client->request('POST', 'http://localhost'), $client);

        self::assertSame('changeIt', $result->getRequestCharged());
    }
}
