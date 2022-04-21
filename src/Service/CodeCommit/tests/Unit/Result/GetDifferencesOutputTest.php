<?php

namespace AsyncAws\CodeCommit\Tests\Unit\Result;

use AsyncAws\CodeCommit\Result\GetDifferencesOutput;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class GetDifferencesOutputTest extends TestCase
{
    public function testGetDifferencesOutput(): void
    {
        // see https://docs.aws.amazon.com/codecommit/latest/APIReference/API_GetDifferences.html
        $response = new SimpleMockedResponse('{
            "differences": [
              {
                 "afterBlob": {
                    "blobId": "abc123",
                    "mode": "100644",
                    "path": "composer.json"
                 },
                 "beforeBlob": {
                    "blobId": "xyz789",
                    "mode": "100755",
                    "path": "composer.lock"
                 },
                 "changeType": "M"
              }
           ],
           "NextToken": "NEXT_TOK"
        }');

        $client = new MockHttpClient($response);
        $result = new GetDifferencesOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('NEXT_TOK', $result->getNextToken());

        $diff = $result->getdifferences(true)->current();
        self::assertSame('abc123', $diff->getAfterBlob()->getBlobId());
        self::assertSame('100644', $diff->getAfterBlob()->getMode());
        self::assertSame('composer.json', $diff->getAfterBlob()->getPath());
        self::assertSame('xyz789', $diff->getBeforeBlob()->getBlobId());
        self::assertSame('100755', $diff->getBeforeBlob()->getMode());
        self::assertSame('composer.lock', $diff->getBeforeBlob()->getPath());
        self::assertSame('M', $diff->getChangeType());
    }
}
