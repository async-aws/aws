<?php

namespace AsyncAws\S3Vectors\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3Vectors\Input\ListVectorsInput;
use AsyncAws\S3Vectors\Result\ListVectorsOutput;
use AsyncAws\S3Vectors\S3VectorsClient;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ListVectorsOutputTest extends TestCase
{
    public function testListVectorsOutput(): void
    {
        $response = new SimpleMockedResponse('{"nextToken":"nxt","vectors":[{"key":"k1","data":{"float32":[1.0,2.0,3.0]},"metadata":{"a":1}}]}');

        $client = new MockHttpClient($response);
        $result = new ListVectorsOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()), new S3VectorsClient(), new ListVectorsInput([]));

        self::assertInstanceOf(ListVectorsOutput::class, $result);
    }
}
