<?php

namespace AsyncAws\Sns\Tests\Integration;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Sns\Input\CreateTopicInput;
use AsyncAws\Sns\Input\DeleteTopicInput;
use AsyncAws\Sns\Input\ListSubscriptionsByTopicInput;
use AsyncAws\Sns\Input\MessageAttributeValue;
use AsyncAws\Sns\Input\PublishInput;
use AsyncAws\Sns\Input\SubscribeInput;
use AsyncAws\Sns\Input\UnsubscribeInput;
use AsyncAws\Sns\SnsClient;
use AsyncAws\Sns\ValueObject\Tag;

class SnsClientTest extends TestCase
{
    public function testCreateTopic(): void
    {
        $client = $this->getClient();

        $input = new CreateTopicInput([
            'Name' => 'change me',
            'Attributes' => ['change me' => 'change me'],
            'Tags' => [new Tag([
                'Key' => 'change me',
                'Value' => 'change me',
            ])],
        ]);
        $result = $client->CreateTopic($input);

        $result->resolve();

        self::assertSame('changeIt', $result->getTopicArn());
    }

    public function testDeleteTopic(): void
    {
        $client = $this->getClient();

        $input = new DeleteTopicInput([
            'TopicArn' => 'change me',
        ]);
        $result = $client->DeleteTopic($input);

        $result->resolve();
    }

    public function testListSubscriptionsByTopic(): void
    {
        $client = $this->getClient();

        $input = new ListSubscriptionsByTopicInput([
            'TopicArn' => 'change me',
            'NextToken' => 'change me',
        ]);
        $result = $client->ListSubscriptionsByTopic($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getSubscriptions());
        self::assertSame('changeIt', $result->getNextToken());
    }

    public function testPublish(): void
    {
        $client = $this->getClient();

        $input = new PublishInput([
            'TopicArn' => 'change me',
            'TargetArn' => 'change me',
            'PhoneNumber' => 'change me',
            'Message' => 'change me',
            'Subject' => 'change me',
            'MessageStructure' => 'change me',
            'MessageAttributes' => ['change me' => new MessageAttributeValue([
                'DataType' => 'change me',
                'StringValue' => 'change me',
                'BinaryValue' => 'change me',
            ])],
        ]);
        $result = $client->Publish($input);

        $result->resolve();

        self::assertStringContainsString('change it', $result->getMessageId());
    }

    public function testSubscribe(): void
    {
        $client = $this->getClient();

        $input = new SubscribeInput([
            'TopicArn' => 'change me',
            'Protocol' => 'change me',
            'Endpoint' => 'change me',
            'Attributes' => ['change me' => 'change me'],
            'ReturnSubscriptionArn' => false,
        ]);
        $result = $client->Subscribe($input);

        $result->resolve();

        self::assertSame('changeIt', $result->getSubscriptionArn());
    }

    public function testUnsubscribe(): void
    {
        $client = $this->getClient();

        $input = new UnsubscribeInput([
            'SubscriptionArn' => 'change me',
        ]);
        $result = $client->Unsubscribe($input);

        $result->resolve();
    }

    private function getClient(): SnsClient
    {
        self::markTestSkipped('No Docker image for SNS');

        return new SnsClient([
            'endpoint' => 'http://localhost',
        ], new NullProvider());
    }
}
