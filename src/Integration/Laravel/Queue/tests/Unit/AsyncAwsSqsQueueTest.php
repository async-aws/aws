<?php

declare(strict_types=1);

namespace AsyncAws\Illuminate\Queue\Tests\Unit;

use AsyncAws\Core\Test\ResultMockFactory;
use AsyncAws\Illuminate\Queue\AsyncAwsSqsQueue;
use AsyncAws\Illuminate\Queue\Job\AsyncAwsSqsJob;
use AsyncAws\Illuminate\Queue\Tests\Resource\CreateUser;
use AsyncAws\Sqs\Enum\QueueAttributeName;
use AsyncAws\Sqs\Result\GetQueueAttributesResult;
use AsyncAws\Sqs\Result\ReceiveMessageResult;
use AsyncAws\Sqs\Result\SendMessageResult;
use AsyncAws\Sqs\SqsClient;
use AsyncAws\Sqs\ValueObject\Message;
use Illuminate\Container\Container;
use PHPUnit\Framework\TestCase;

class AsyncAwsSqsQueueTest extends TestCase
{
    private $queue = 'https://sqs.us-east-2.amazonaws.com/123456789012/invoice';

    public function testSize()
    {
        $result = ResultMockFactory::create(GetQueueAttributesResult::class, [
            'Attributes' => [QueueAttributeName::APPROXIMATE_NUMBER_OF_MESSAGES => 4],
        ]);

        $sqs = $this->getMockBuilder(SqsClient::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getQueueAttributes'])
            ->getMock();

        $sqs->expects(self::once())
            ->method('getQueueAttributes')
            ->with(self::callback(function (array $input) {
                if ($input['QueueUrl'] !== $this->queue) {
                    return false;
                }

                if (!isset($input['AttributeNames'])) {
                    return false;
                }

                if ([QueueAttributeName::APPROXIMATE_NUMBER_OF_MESSAGES] !== $input['AttributeNames']) {
                    return false;
                }

                return true;
            }))
            ->willReturn($result);

        $queue = new AsyncAwsSqsQueue($sqs, $this->queue);
        self::assertEquals(4, $queue->size());
    }

    public function testPush()
    {
        $result = ResultMockFactory::create(SendMessageResult::class, [
            'MessageId' => 'my-id',
        ]);

        $sqs = $this->getMockBuilder(SqsClient::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['sendMessage'])
            ->getMock();

        $sqs->expects(self::once())
            ->method('sendMessage')
            ->with(self::callback(function (array $input) {
                if ($input['QueueUrl'] !== $this->queue) {
                    return false;
                }

                if (!isset($input['MessageBody'])) {
                    return false;
                }

                // verify that body is serialized.
                $body = json_decode($input['MessageBody'], true);
                $this->assertArrayHasKey('displayName', $body);
                $this->assertArrayHasKey('job', $body);
                $this->assertArrayHasKey('data', $body);

                if (CreateUser::class !== $body['displayName']) {
                    return false;
                }

                return true;
            }))
            ->willReturn($result);

        $queue = new AsyncAwsSqsQueue($sqs, $this->queue);
        $output = $queue->push(new CreateUser('Alic', 'alice@example.com'));
        self::assertEquals('my-id', $output);
    }

    public function testPushRaw()
    {
        $result = ResultMockFactory::create(SendMessageResult::class, [
            'MessageId' => 'my-id',
        ]);

        $sqs = $this->getMockBuilder(SqsClient::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['sendMessage'])
            ->getMock();

        $sqs->expects(self::once())
            ->method('sendMessage')
            ->with(self::callback(function (array $input) {
                if ($input['QueueUrl'] !== $this->queue) {
                    return false;
                }

                if (!isset($input['MessageBody'])) {
                    return false;
                }

                if ('payload' !== $input['MessageBody']) {
                    return false;
                }

                return true;
            }))
            ->willReturn($result);

        $queue = new AsyncAwsSqsQueue($sqs, $this->queue);
        self::assertEquals('my-id', $queue->pushRaw('payload'));
    }

    public function testLater()
    {
        $result = ResultMockFactory::create(SendMessageResult::class, [
            'MessageId' => 'my-id',
        ]);

        $sqs = $this->getMockBuilder(SqsClient::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['sendMessage'])
            ->getMock();

        $sqs->expects(self::once())
            ->method('sendMessage')
            ->with(self::callback(function (array $input) {
                if ($input['QueueUrl'] !== $this->queue) {
                    return false;
                }

                if (47 !== $input['DelaySeconds']) {
                    return false;
                }

                if (!isset($input['MessageBody'])) {
                    return false;
                }

                if (false === strpos($input['MessageBody'], 'my-custom-payload')) {
                    return false;
                }

                return true;
            }))
            ->willReturn($result);

        $queue = new AsyncAwsSqsQueue($sqs, $this->queue);
        self::assertEquals('my-id', $queue->later(47, 'my-custom-payload'));
    }

    public function testPop()
    {
        $data = [
            'Body' => '{"uuid":"401eddde-fd9e-4dcd-83f3-d1ffe2ec4259","displayName":"AsyncAws\\\Illuminate\\\Queue\\\Tests\\\Resource\\\CreateUser","job":"Illuminate\\\Queue\\\CallQueuedHandler@call","maxTries":null,"maxExceptions":null,"delay":null,"timeout":null,"timeoutAt":null,"data":{"commandName":"AsyncAws\\\Illuminate\\\Queue\\\Tests\\\Resource\\\CreateUser","command":"O:51:\"AsyncAws\\\Illuminate\\\Queue\\\Tests\\\Resource\\\CreateUser\":2:{s:57:\"\u0000AsyncAws\\\Illuminate\\\Queue\\\Tests\\\Resource\\\CreateUser\u0000name\";s:4:\"Alic\";s:58:\"\u0000AsyncAws\\\Illuminate\\\Queue\\\Tests\\\Resource\\\CreateUser\u0000email\";s:17:\"alice@example.com\";}"}}',
            'MessageId' => 'message-id-example',
            'Attributes' => ['ApproximateReceiveCount' => '3'],
            'ReceiptHandle' => '123',
        ];
        $message = new Message($data);

        $result = ResultMockFactory::create(ReceiveMessageResult::class, [
            'Messages' => [$message],
        ]);

        $sqs = $this->getMockBuilder(SqsClient::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['receiveMessage'])
            ->getMock();

        $sqs->expects(self::once())
            ->method('receiveMessage')
            ->with(self::callback(function (array $input) {
                if ($input['QueueUrl'] !== $this->queue) {
                    return false;
                }

                if (!isset($input['AttributeNames'])) {
                    return false;
                }

                if (['All'] !== $input['AttributeNames']) {
                    return false;
                }

                return true;
            }))
            ->willReturn($result);

        $queue = new AsyncAwsSqsQueue($sqs, $this->queue);
        $queue->setContainer(new Container());
        $queue->setConnectionName('connection-name');
        $job = $queue->pop();

        self::assertInstanceOf(AsyncAwsSqsJob::class, $job);
        self::assertEquals($data, $job->getSqsJob());
    }
}
