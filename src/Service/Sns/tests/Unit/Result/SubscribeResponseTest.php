<?php

namespace AsyncAws\Sns\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Sns\Result\SubscribeResponse;
use Symfony\Component\HttpClient\MockHttpClient;

class SubscribeResponseTest extends TestCase
{
    public function testSubscribeResponse(): void
    {
        // see https://docs.aws.amazon.com/sns/latest/api/API_Subscribe.html
        $response = new SimpleMockedResponse('<SubscribeResponse xmlns="https://sns.amazonaws.com/doc/2010-03-31/">
    <SubscribeResult>
        <SubscriptionArn>arn:aws:sns:us-west-2:123456789012:MyTopic:6b0e71bd-7e97-4d97-80ce-4a0994e55286</SubscriptionArn>
    </SubscribeResult>
    <ResponseMetadata>
        <RequestId>c4407779-24a4-56fa-982c-3d927f93a775</RequestId>
    </ResponseMetadata>
</SubscribeResponse>');

        $client = new MockHttpClient($response);
        $result = new SubscribeResponse(new Response($client->request('POST', 'http://localhost'), $client));

        self::assertSame('arn:aws:sns:us-west-2:123456789012:MyTopic:6b0e71bd-7e97-4d97-80ce-4a0994e55286', $result->getSubscriptionArn());
    }
}
