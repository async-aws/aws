<?php

namespace AsyncAws\Sns\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Sns\Result\ListSubscriptionsByTopicResponse;
use Symfony\Component\HttpClient\MockHttpClient;

class ListSubscriptionsByTopicResponseTest extends TestCase
{
    public function testListSubscriptionsByTopicResponse(): void
    {
        // see https://docs.aws.amazon.com/sns/latest/api/API_ListSubscriptionsByTopic.html
        $response = new SimpleMockedResponse('<ListSubscriptionsByTopicResponse xmlns="https://sns.amazonaws.com/doc/2010-03-31/">
    <ListSubscriptionsByTopicResult>
        <Subscriptions>
            <member>
                <TopicArn>arn:aws:sns:us-east-2:123456789012:My-Topic</TopicArn>
                <Protocol>email</Protocol>
                <SubscriptionArn>arn:aws:sns:us-east-2:123456789012:My-Topic:80289ba6-0fd4-4079-afb4-ce8c8260f0ca</SubscriptionArn>
                <Owner>123456789012</Owner>
                <Endpoint>example@amazon.com</Endpoint>
            </member>
        </Subscriptions>
    </ListSubscriptionsByTopicResult>
    <ResponseMetadata>
        <RequestId>b9275252-3774-11df-9540-99d0768312d3</RequestId>
    </ResponseMetadata>
</ListSubscriptionsByTopicResponse>');

        $client = new MockHttpClient($response);
        $result = new ListSubscriptionsByTopicResponse(new Response($client->request('POST', 'http://localhost'), $client));

        $subcsribtion = \iterator_to_array($result->getSubscriptions(true))[0];

        self::assertSame('arn:aws:sns:us-east-2:123456789012:My-Topic', $subcsribtion->getTopicArn());
        self::assertSame('email', $subcsribtion->getProtocol());
        self::assertSame('arn:aws:sns:us-east-2:123456789012:My-Topic:80289ba6-0fd4-4079-afb4-ce8c8260f0ca', $subcsribtion->getSubscriptionArn());
        self::assertSame('123456789012', $subcsribtion->getOwner());
        self::assertSame('example@amazon.com', $subcsribtion->getEndpoint());
    }
}
