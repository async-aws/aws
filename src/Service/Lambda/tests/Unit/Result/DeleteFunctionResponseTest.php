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
        // see https://docs.aws.amazon.com/lambda/latest/api/API_DeleteFunction.html
        $response = new SimpleMockedResponse('{
            "StatusCode": 200
        }');

        $client = new MockHttpClient($response);
        $result = new DeleteFunctionResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame(200, $result->getStatusCode());
    }
}
