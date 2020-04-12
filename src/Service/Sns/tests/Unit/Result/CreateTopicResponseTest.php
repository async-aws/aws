<?php

namespace AsyncAws\Sns\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Sns\Result\CreateTopicResponse;
use Symfony\Component\HttpClient\MockHttpClient;

class CreateTopicResponseTest extends TestCase
{
    public function testCreateTopicResponse(): void
    {
        // see https://docs.aws.amazon.com/sns/latest/api/API_CreateTopic.html
        $response = new SimpleMockedResponse('<CreateTopicResponse xmlns="https://sns.amazonaws.com/doc/2010-03-31/">
    <CreateTopicResult>
        <TopicArn>arn:aws:sns:us-east-2:123456789012:My-Topic</TopicArn>
    </CreateTopicResult>
    <ResponseMetadata>
        <RequestId>a8dec8b3-33a4-11df-8963-01868b7c937a</RequestId>
    </ResponseMetadata>
</CreateTopicResponse>');

        $client = new MockHttpClient($response);
        $result = new CreateTopicResponse(new Response($client->request('POST', 'http://localhost'), $client));

        self::assertSame('arn:aws:sns:us-east-2:123456789012:My-Topic', $result->getTopicArn());
    }
}
