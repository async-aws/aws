<?php

namespace AsyncAws\AppSync\Tests\Unit\Result;

use AsyncAws\AppSync\Result\DeleteResolverResponse;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class DeleteResolverResponseTest extends TestCase
{
    public function testDeleteResolverResponse(): void
    {
        // see https://docs.aws.amazon.com/appsync/latest/APIReference/API_DeleteResolver.html
        $response = new SimpleMockedResponse();

        $client = new MockHttpClient($response);
        $result = new DeleteResolverResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertEquals(200, $result->info()['response']->getStatusCode());
    }
}
