<?php

namespace AsyncAws\Sqs\Tests\Unit;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Result;
use AsyncAws\Sqs\Input\ChangeMessageVisibilityRequest;
use AsyncAws\Sqs\Input\CreateQueueRequest;
use AsyncAws\Sqs\Input\DeleteMessageRequest;
use AsyncAws\Sqs\Input\DeleteQueueRequest;
use AsyncAws\Sqs\Input\GetQueueAttributesRequest;
use AsyncAws\Sqs\Input\GetQueueUrlRequest;
use AsyncAws\Sqs\Input\ListQueuesRequest;
use AsyncAws\Sqs\Input\PurgeQueueRequest;
use AsyncAws\Sqs\Input\ReceiveMessageRequest;
use AsyncAws\Sqs\Input\SendMessageRequest;
use AsyncAws\Sqs\Result\CreateQueueResult;
use AsyncAws\Sqs\Result\GetQueueAttributesResult;
use AsyncAws\Sqs\Result\GetQueueUrlResult;
use AsyncAws\Sqs\Result\ListQueuesResult;
use AsyncAws\Sqs\Result\ReceiveMessageResult;
use AsyncAws\Sqs\Result\SendMessageResult;
use AsyncAws\Sqs\SqsClient;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

class SqsClientTest extends TestCase
{
    public function testChangeMessageVisibility(): void
    {
        $client = new SqsClient([], new NullProvider(), new MockHttpClient());

        $input = new ChangeMessageVisibilityRequest([
            'QueueUrl' => 'change me',
            'ReceiptHandle' => 'change me',
            'VisibilityTimeout' => 1337,
        ]);
        $result = $client->ChangeMessageVisibility($input);

        self::assertInstanceOf(Result::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testCreateQueue(): void
    {
        $client = new SqsClient([], new NullProvider(), new MockHttpClient());

        $input = new CreateQueueRequest([
            'QueueName' => 'change me',

        ]);
        $result = $client->CreateQueue($input);

        self::assertInstanceOf(CreateQueueResult::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testDeleteMessage(): void
    {
        $client = new SqsClient([], new NullProvider(), new MockHttpClient());

        $input = new DeleteMessageRequest([
            'QueueUrl' => 'change me',
            'ReceiptHandle' => 'change me',
        ]);
        $result = $client->DeleteMessage($input);

        self::assertInstanceOf(Result::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testDeleteQueue(): void
    {
        $client = new SqsClient([], new NullProvider(), new MockHttpClient());

        $input = new DeleteQueueRequest([
            'QueueUrl' => 'change me',
        ]);
        $result = $client->DeleteQueue($input);

        self::assertInstanceOf(Result::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testGetQueueAttributes(): void
    {
        $client = new SqsClient([], new NullProvider(), new MockHttpClient());

        $input = new GetQueueAttributesRequest([
            'QueueUrl' => 'change me',

        ]);
        $result = $client->GetQueueAttributes($input);

        self::assertInstanceOf(GetQueueAttributesResult::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testGetQueueUrl(): void
    {
        $client = new SqsClient([], new NullProvider(), new MockHttpClient());

        $input = new GetQueueUrlRequest([
            'QueueName' => 'change me',

        ]);
        $result = $client->GetQueueUrl($input);

        self::assertInstanceOf(GetQueueUrlResult::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testListQueues(): void
    {
        $client = new SqsClient([], new NullProvider(), new MockHttpClient());

        $input = new ListQueuesRequest([

        ]);
        $result = $client->ListQueues($input);

        self::assertInstanceOf(ListQueuesResult::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testPurgeQueue(): void
    {
        $client = new SqsClient([], new NullProvider(), new MockHttpClient());

        $input = new PurgeQueueRequest([
            'QueueUrl' => 'change me',
        ]);
        $result = $client->PurgeQueue($input);

        self::assertInstanceOf(Result::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testReceiveMessage(): void
    {
        $client = new SqsClient([], new NullProvider(), new MockHttpClient());

        $input = new ReceiveMessageRequest([
            'QueueUrl' => 'change me',

        ]);
        $result = $client->ReceiveMessage($input);

        self::assertInstanceOf(ReceiveMessageResult::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testSendMessage(): void
    {
        $client = new SqsClient([], new NullProvider(), new MockHttpClient());

        $input = new SendMessageRequest([
            'QueueUrl' => 'change me',
            'MessageBody' => 'change me',

        ]);
        $result = $client->SendMessage($input);

        self::assertInstanceOf(SendMessageResult::class, $result);
        self::assertFalse($result->info()['resolved']);
    }
}
