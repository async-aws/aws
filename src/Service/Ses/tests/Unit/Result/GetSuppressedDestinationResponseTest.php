<?php

namespace AsyncAws\Ses\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Ses\Result\GetSuppressedDestinationResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class GetSuppressedDestinationResponseTest extends TestCase
{
    public function testGetSuppressedDestinationResponse(): void
    {
        // see https://docs.aws.amazon.com/ses/latest/APIReference-V2/API_GetSuppressedDestination.html
        $response = new SimpleMockedResponse('{
   "SuppressedDestination": {
      "Attributes": {
         "FeedbackId": "feedback-id",
         "MessageId": "message-id"
      },
      "EmailAddress": "test@example.com",
      "LastUpdateTime": 1234567890,
      "Reason": "BOUNCE"
   }
}');

        $client = new MockHttpClient($response);
        $result = new GetSuppressedDestinationResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        $dest = $result->getSuppressedDestination();

        self::assertSame('test@example.com', $dest->getEmailAddress());
        self::assertSame(1234567890, $dest->getLastUpdateTime()->getTimestamp());
        self::assertSame('BOUNCE', $dest->getReason());
    }
}
