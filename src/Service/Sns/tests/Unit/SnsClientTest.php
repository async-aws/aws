<?php

namespace AsyncAws\Sns\Tests\Unit;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Result;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Sns\Input\CreatePlatformEndpointInput;
use AsyncAws\Sns\Input\CreateTopicInput;
use AsyncAws\Sns\Input\DeleteEndpointInput;
use AsyncAws\Sns\Input\DeleteTopicInput;
use AsyncAws\Sns\Input\ListSubscriptionsByTopicInput;
use AsyncAws\Sns\Input\PublishBatchInput;
use AsyncAws\Sns\Input\PublishInput;
use AsyncAws\Sns\Input\SubscribeInput;
use AsyncAws\Sns\Input\UnsubscribeInput;
use AsyncAws\Sns\Result\CreateEndpointResponse;
use AsyncAws\Sns\Result\CreateTopicResponse;
use AsyncAws\Sns\Result\ListSubscriptionsByTopicResponse;
use AsyncAws\Sns\Result\PublishBatchResponse;
use AsyncAws\Sns\Result\PublishResponse;
use AsyncAws\Sns\Result\SubscribeResponse;
use AsyncAws\Sns\SnsClient;
use AsyncAws\Sns\ValueObject\PublishBatchRequestEntry;
use Symfony\Component\HttpClient\MockHttpClient;

class SnsClientTest extends TestCase
{
    public function testCreatePlatformEndpoint(): void
    {
        $client = new SnsClient([], new NullProvider(), new MockHttpClient());

        $input = new CreatePlatformEndpointInput([
            'PlatformApplicationArn' => 'change me',
            'Token' => 'change me',

        ]);
        $result = $client->CreatePlatformEndpoint($input);

        self::assertInstanceOf(CreateEndpointResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testCreateTopic(): void
    {
        $client = new SnsClient([], new NullProvider(), new MockHttpClient());

        $input = new CreateTopicInput([
            'Name' => 'change me',

        ]);
        $result = $client->CreateTopic($input);

        self::assertInstanceOf(CreateTopicResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testDeleteEndpoint(): void
    {
        $client = new SnsClient([], new NullProvider(), new MockHttpClient());

        $input = new DeleteEndpointInput([
            'EndpointArn' => 'change me',
        ]);
        $result = $client->DeleteEndpoint($input);

        self::assertInstanceOf(Result::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testDeleteTopic(): void
    {
        $client = new SnsClient([], new NullProvider(), new MockHttpClient());

        $input = new DeleteTopicInput([
            'TopicArn' => 'change me',
        ]);
        $result = $client->DeleteTopic($input);

        self::assertInstanceOf(Result::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testListSubscriptionsByTopic(): void
    {
        $client = new SnsClient([], new NullProvider(), new MockHttpClient());

        $input = new ListSubscriptionsByTopicInput([
            'TopicArn' => 'change me',

        ]);
        $result = $client->ListSubscriptionsByTopic($input);

        self::assertInstanceOf(ListSubscriptionsByTopicResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testPublish(): void
    {
        $client = new SnsClient([], new NullProvider(), new MockHttpClient());

        $input = new PublishInput([

            'Message' => 'change me',

        ]);
        $result = $client->Publish($input);

        self::assertInstanceOf(PublishResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testPublishBatch(): void
    {
        $client = new SnsClient([], new NullProvider(), new MockHttpClient());

        $input = new PublishBatchInput([
            'TopicArn' => 'change me',
            'PublishBatchRequestEntries' => [new PublishBatchRequestEntry([
                'Id' => 'change me',
                'Message' => 'change me',

            ])],
        ]);
        $result = $client->publishBatch($input);

        self::assertInstanceOf(PublishBatchResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testSubscribe(): void
    {
        $client = new SnsClient([], new NullProvider(), new MockHttpClient());

        $input = new SubscribeInput([
            'TopicArn' => 'change me',
            'Protocol' => 'change me',

        ]);
        $result = $client->Subscribe($input);

        self::assertInstanceOf(SubscribeResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testUnsubscribe(): void
    {
        $client = new SnsClient([], new NullProvider(), new MockHttpClient());

        $input = new UnsubscribeInput([
            'SubscriptionArn' => 'change me',
        ]);
        $result = $client->Unsubscribe($input);

        self::assertInstanceOf(Result::class, $result);
        self::assertFalse($result->info()['resolved']);
    }
}
