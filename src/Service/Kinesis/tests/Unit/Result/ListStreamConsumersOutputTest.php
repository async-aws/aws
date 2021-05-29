<?php

namespace AsyncAws\Kinesis\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kinesis\Input\ListStreamConsumersInput;
use AsyncAws\Kinesis\KinesisClient;
use AsyncAws\Kinesis\Result\ListStreamConsumersOutput;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ListStreamConsumersOutputTest extends TestCase
{
    public function testListStreamConsumersOutput(): void
    {
        // see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_ListStreamConsumers.html
        $response = new SimpleMockedResponse('{
   "Consumers": [
      {
         "ConsumerARN": "arn",
         "ConsumerCreationTimestamp": 123456,
         "ConsumerName": "name",
         "ConsumerStatus": "status"
      }
   ],
   "NextToken": "string"
}');

        $client = new MockHttpClient($response);
        $result = new ListStreamConsumersOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()), new KinesisClient(), new ListStreamConsumersInput([]));

        // self::assertTODO(expected, $result->getConsumers());
        self::assertCount(1, $result->getConsumers(true));
        self::assertSame('arn', iterator_to_array($result->getConsumers(true))[0]->getConsumerArn());
    }
}
