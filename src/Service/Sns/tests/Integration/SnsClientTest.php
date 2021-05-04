<?php

namespace AsyncAws\Sns\Tests\Integration;

use AsyncAws\Core\Credentials\Credentials;
use AsyncAws\Core\Exception\Http\ClientException;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Sns\Input\CreatePlatformEndpointInput;
use AsyncAws\Sns\Input\CreateTopicInput;
use AsyncAws\Sns\Input\DeleteEndpointInput;
use AsyncAws\Sns\Input\DeleteTopicInput;
use AsyncAws\Sns\Input\ListSubscriptionsByTopicInput;
use AsyncAws\Sns\Input\PublishInput;
use AsyncAws\Sns\Input\SubscribeInput;
use AsyncAws\Sns\Input\UnsubscribeInput;
use AsyncAws\Sns\SnsClient;
use AsyncAws\Sns\ValueObject\Tag;

class SnsClientTest extends TestCase
{
    private $topicArn = null;

    public function setUp(): void
    {
        parent::setUp();
        $this->topicArn = $this->getClient()->createTopic(['Name' => 'async-aws'])->getTopicArn();
    }

    public function tearDown(): void
    {
        parent::tearDown();

        try {
            $this->getClient()->deleteTopic(['TopicArn' => $this->topicArn]);
        } catch (ClientException $e) {
            if (404 !== $e->getCode()) {
                throw $e;
            }
        }
    }

    public function testCreatePlatformEndpoint(): void
    {
        self::markTestIncomplete('Needs to create a platform in order to create an endpoint');
        $client = $this->getClient();

        $input = new CreatePlatformEndpointInput([
            'PlatformApplicationArn' => 'change me',
            'Token' => 'change me',
            'CustomUserData' => 'change me',
            'Attributes' => ['change me' => 'change me'],
        ]);
        $result = $client->CreatePlatformEndpoint($input);

        $result->resolve();

        self::assertSame('changeIt', $result->getEndpointArn());
    }

    public function testCreateTopic(): void
    {
        $client = $this->getClient();

        $input = new CreateTopicInput([
            'Name' => 'my-topic',
            'Attributes' => ['attribute1' => 'value 1'],
            'Tags' => [new Tag([
                'Key' => 'group',
                'Value' => 'demo',
            ])],
        ]);
        $result = $client->CreateTopic($input);

        self::assertSame('arn:aws:sns:us-east-1:000000000000:my-topic', $result->getTopicArn());
    }

    public function testDeleteEndpoint(): void
    {
        self::markTestIncomplete('Needs to create an endpoint in order to delete it');
        $client = $this->getClient();

        $input = new DeleteEndpointInput([
            'EndpointArn' => 'change me',
        ]);
        $result = $client->DeleteEndpoint($input);

        $result->resolve();
    }

    public function testDeleteTopic(): void
    {
        $client = $this->getClient();

        $input = new DeleteTopicInput([
            'TopicArn' => $this->topicArn,
        ]);
        $result = $client->DeleteTopic($input);

        self::expectNotToPerformAssertions();
        $result->resolve();
    }

    public function testListSubscriptionsByTopic(): void
    {
        $client = $this->getClient();

        $client->Subscribe([
            'TopicArn' => $this->topicArn,
            'Protocol' => 'http',
            'Endpoint' => 'http://async-aws.com',
            'ReturnSubscriptionArn' => true,
        ]);

        $input = new ListSubscriptionsByTopicInput([
            'TopicArn' => $this->topicArn,
        ]);
        $result = $client->ListSubscriptionsByTopic($input);

        self::assertCount(1, $result->getSubscriptions());
        $subscription = iterator_to_array($result->getSubscriptions())[0];
        self::assertSame('http://async-aws.com', $subscription->getEndpoint());
    }

    public function testPublish(): void
    {
        $client = $this->getClient();

        $input = new PublishInput([
            'TopicArn' => $this->topicArn,
            'Message' => 'Hello world',
            'Subject' => 'Read this',
        ]);
        $result = $client->Publish($input);

        self::assertNotEmpty($result->getMessageId());
    }

    public function testSubscribe(): void
    {
        $client = $this->getClient();

        $input = new SubscribeInput([
            'TopicArn' => $this->topicArn,
            'Protocol' => 'http',
            'Endpoint' => 'http://async-aws.com',
            'ReturnSubscriptionArn' => true,
        ]);
        $result = $client->Subscribe($input);

        self::assertStringContainsString('arn:aws:sns:us-east-1:000000000000:async-aws:', $result->getSubscriptionArn());

        self::assertCount(1, $client->listSubscriptionsByTopic(['TopicArn' => $this->topicArn]));
    }

    public function testUnsubscribe(): void
    {
        $client = $this->getClient();

        $result = $client->Subscribe([
            'TopicArn' => $this->topicArn,
            'Protocol' => 'http',
            'Endpoint' => 'http://async-aws.com',
            'ReturnSubscriptionArn' => true,
        ]);

        $result->resolve();
        self::assertCount(1, $client->listSubscriptionsByTopic(['TopicArn' => $this->topicArn]));

        $input = new UnsubscribeInput([
            'SubscriptionArn' => $result->getSubscriptionArn(),
        ]);
        $result = $client->Unsubscribe($input);

        $result->resolve();
        self::assertCount(0, $client->listSubscriptionsByTopic(['TopicArn' => $this->topicArn]));
    }

    private function getClient(): SnsClient
    {
        return new SnsClient([
            'endpoint' => 'http://localhost:4573',
        ], new Credentials('aws_id', 'aws_secret'));
    }
}
