<?php

namespace AsyncAws\Kinesis\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kinesis\Result\DescribeStreamConsumerOutput;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class DescribeStreamConsumerOutputTest extends TestCase
{
    public function testDescribeStreamConsumerOutput(): void
    {
        // see https://docs.aws.amazon.com/kinesis/latest/APIReference/API_DescribeStreamConsumer.html
        $response = new SimpleMockedResponse('{
   "ConsumerDescription": {
      "ConsumerARN": "arn",
      "ConsumerCreationTimestamp": 123456,
      "ConsumerName": "name",
      "ConsumerStatus": "status",
      "StreamARN": "arn"
   }
}');

        $client = new MockHttpClient($response);
        $result = new DescribeStreamConsumerOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('arn', $result->getConsumerDescription()->getStreamArn());
    }
}
