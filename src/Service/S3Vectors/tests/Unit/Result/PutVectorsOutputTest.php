<?php

namespace AsyncAws\S3Vectors\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3Vectors\Result\PutVectorsOutput;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class PutVectorsOutputTest extends TestCase
{
    public function testPutVectorsOutput(): void
    {
        $response = new SimpleMockedResponse('{}');

        $client = new MockHttpClient($response);
        $result = new PutVectorsOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertInstanceOf(PutVectorsOutput::class, $result);
    }
}
