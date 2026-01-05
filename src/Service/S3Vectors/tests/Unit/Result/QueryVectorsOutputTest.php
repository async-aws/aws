<?php

namespace AsyncAws\S3Vectors\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3Vectors\Result\QueryVectorsOutput;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class QueryVectorsOutputTest extends TestCase
{
    public function testQueryVectorsOutput(): void
    {
        $response = new SimpleMockedResponse('{"vectors":[{"distance":0.123,"key":"vec1","metadata":{"title":"a"}},{"distance":0.456,"key":"vec2"}],"distanceMetric":"cosine"}');

        $client = new MockHttpClient($response);
        $result = new QueryVectorsOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertInstanceOf(QueryVectorsOutput::class, $result);
    }
}
