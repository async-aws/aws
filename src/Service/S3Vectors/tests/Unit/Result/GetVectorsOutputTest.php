<?php

namespace AsyncAws\S3Vectors\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3Vectors\Result\GetVectorsOutput;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class GetVectorsOutputTest extends TestCase
{
    public function testGetVectorsOutput(): void
    {
        $response = new SimpleMockedResponse('{"vectors":[{"key":"k1","data":{"float32":[0.1,0.2,0.3]},"metadata":{"foo":"bar"}}]}');

        $client = new MockHttpClient($response);
        $result = new GetVectorsOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertInstanceOf(GetVectorsOutput::class, $result);
    }
}
