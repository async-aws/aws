<?php

namespace AsyncAws\S3\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Result\PutObjectAclOutput;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class PutObjectOutputTest extends TestCase
{
    public function testPutObjectOutput(): void
    {
        // see https://docs.aws.amazon.com/AmazonS3/latest/API/API_PutObject.html
        $response = new SimpleMockedResponse('', [
            'x-amz-id-2' => 'RUxG2sZJUfS+ezeAS2i0Xj6w/ST6xqF/8pFNHjTjTrECW56SCAUWGg+7QLVoj1GH',
            'x-amz-request-id' => '8D017A90827290BA',
            'x-amz-request-charged' => 'requester',
            'Date' => 'Fri, 13 Apr 2012 05:40:25 GMT',
            'ETag' => '"dd038b344cf9553547f8b395a814b274"',
        ]);

        $client = new MockHttpClient($response);
        $result = new PutObjectAclOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('requester', $result->getRequestCharged());
    }
}
