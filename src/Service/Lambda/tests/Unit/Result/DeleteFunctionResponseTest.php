<?php

namespace AsyncAws\Lambda\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Lambda\Result\DeleteFunctionResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class DeleteFunctionResponseTest extends TestCase
{
    public function testDeleteFunctionResponse(): void
    {
        self::fail('Not implemented');

        // see https://docs.aws.amazon.com/lambda/latest/APIReference/API_DeleteFunction.html
        $response = new SimpleMockedResponse('{
            "change": "it"
        }');

        $client = new MockHttpClient($response);
        $result = new DeleteFunctionResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame(1337, $result->getStatusCode());
    }
}
