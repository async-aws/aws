<?php

namespace AsyncAws\S3Vectors\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3Vectors\Result\DeleteVectorsOutput;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class DeleteVectorsOutputTest extends TestCase
{
    public function testDeleteVectorsOutput(): void
    {
        $response = new SimpleMockedResponse('{}');

        $client = new MockHttpClient($response);
        $result = new DeleteVectorsOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertInstanceOf(DeleteVectorsOutput::class, $result);
    }
}
