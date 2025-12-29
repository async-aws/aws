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
        self::fail('Not implemented');

        // see https://docs.aws.amazon.com/AmazonS3/latest/API/API_Operations_Amazon_S3_Vectors.html/API_ListVectors.html
        $response = new SimpleMockedResponse('{
            "change": "it"
        }');

        $client = new MockHttpClient($response);
        $result = new ListVectorsOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()), new S3VectorsClient(), new ListVectorsInput([]));

        self::assertSame('changeIt', $result->getNextToken());
        // self::assertTODO(expected, $result->getVectors());
    }
}
