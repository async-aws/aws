<?php

namespace AsyncAws\Ses\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Ses\Result\DeleteSuppressedDestinationResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class DeleteSuppressedDestinationResponseTest extends TestCase
{
    public function testDeleteSuppressedDestinationResponse(): void
    {
        // see https://docs.aws.amazon.com/ses/latest/APIReference-V2/API_DeleteSuppressedDestination.html
        $response = new SimpleMockedResponse();

        $client = new MockHttpClient($response);
        $result = new DeleteSuppressedDestinationResponse(new Response($client->request('DELETE', 'http://localhost'), $client, new NullLogger()));

        self::assertTrue($result->resolve());
    }
}
