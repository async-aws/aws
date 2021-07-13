<?php

namespace AsyncAws\Kinesis\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kinesis\Result\RegisterStreamConsumerOutput;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class RegisterStreamConsumerOutputTest extends TestCase
{
    public function testRegisterStreamConsumerOutput(): void
    {
        // see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_RegisterStreamConsumer.html
        $response = new SimpleMockedResponse('{
   "Consumer": {
      "ConsumerARN": "arn",
      "ConsumerCreationTimestamp": 1234567,
      "ConsumerName": "name",
      "ConsumerStatus": "status"
   }
}');

        $client = new MockHttpClient($response);
        $result = new RegisterStreamConsumerOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('arn', $result->getConsumer()->getConsumerArn());
    }
}
