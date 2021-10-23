<?php

namespace AsyncAws\XRay\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\XRay\Result\PutTraceSegmentsResult;
use AsyncAws\XRay\ValueObject\UnprocessedTraceSegment;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class PutTraceSegmentsResultTest extends TestCase
{
    public function testPutTraceSegmentsResult(): void
    {
        // see https://docs.aws.amazon.com/xray/latest/APIReference/API_PutTraceSegments.html
        $response = new SimpleMockedResponse('{
   "UnprocessedTraceSegments": [ 
      { 
         "ErrorCode": "1",
         "Id": "1111111111111111",
         "Message": "Error"
      }
   ]
}');

        $client = new MockHttpClient($response);
        $result = new PutTraceSegmentsResult(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));
        $unprocessedTraceSegments = $result->getUnprocessedTraceSegments();

        self::assertIsArray($unprocessedTraceSegments);
        self::assertCount(1, $unprocessedTraceSegments);
        self::assertInstanceOf(UnprocessedTraceSegment::class, $unprocessedTraceSegments[0]);
        self::assertSame('1', $unprocessedTraceSegments[0]->getErrorCode());
        self::assertSame('1111111111111111', $unprocessedTraceSegments[0]->getId());
        self::assertSame('Error', $unprocessedTraceSegments[0]->getMessage());
    }
}
