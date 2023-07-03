<?php

namespace AsyncAws\Sns\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Sns\Input\ListTopicsInput;
use AsyncAws\Sns\Result\ListTopicsResponse;
use AsyncAws\Sns\SnsClient;
use AsyncAws\Sns\ValueObject\Topic;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ListTopicsResponseTest extends TestCase
{
    public function testListTopicsResponse(): void
    {
        // see https://docs.aws.amazon.com/sns/latest/api/API_ListTopics.html
        $response = new SimpleMockedResponse('<ListTopicsResponse xmlns="http://sns.amazonaws.com/doc/2010-03-31/">
  <ListTopicsResult>
    <Topics>
      <member>
        <TopicArn>arn:aws:sns:eu-west-1:000000000000:MyTopic1</TopicArn>
      </member>
      <member>
        <TopicArn>arn:aws:sns:eu-west-1:000000000000:MyTopic2</TopicArn>
      </member>
    </Topics>
  </ListTopicsResult>
</ListTopicsResponse>');

        $client = new MockHttpClient($response);
        $result = new ListTopicsResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()), new SnsClient(), new ListTopicsInput([]));

        $expected = [
            new Topic(['TopicArn' => 'arn:aws:sns:eu-west-1:000000000000:MyTopic1']),
            new Topic(['TopicArn' => 'arn:aws:sns:eu-west-1:000000000000:MyTopic2']),
        ];

        self::assertEquals($expected, iterator_to_array($result->getTopics()));
    }
}
