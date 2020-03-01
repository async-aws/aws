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
        // see https://docs.aws.amazon.com/AmazonS3/latest/API/API_PutObjectAcl.html
        $response = new SimpleMockedResponse('', [
            'x-amz-id-2' => 'eftixk72aD6Ap51T9AS1ed4OpIszj7UDNEHGran',
            'x-amz-request-id' => '318BC8BC148832E5',
            'x-amz-version-id' => '3/L4kqtJlcpXrof3vjVBH40Nr8X8gdRQBpUMLUo',
            'x-amz-request-charged' => 'requester',
            'Date' => 'Wed, 28 Oct 2009 22:32:00 GMT',
            'Last-Modified' => 'Sun, 1 Jan 2006 12:00:00 GMT',
            'Content-Length' => '0',
        ]);

        $client = new MockHttpClient($response);
        $result = new PutObjectAclOutput($client->request('POST', 'http://localhost'), $client);

        self::assertSame('requester', $result->getRequestCharged());
    }
}
