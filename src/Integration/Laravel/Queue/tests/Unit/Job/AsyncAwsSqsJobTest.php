<?php

declare(strict_types=1);

namespace AsyncAws\Illuminate\Queue\Tests\Unit\Job;

use AsyncAws\Illuminate\Queue\Job\AsyncAwsSqsJob;
use AsyncAws\Sqs\SqsClient;
use AsyncAws\Sqs\ValueObject\Message;
use Illuminate\Container\Container;
use PHPUnit\Framework\TestCase;

class AsyncAwsSqsJobTest extends TestCase
{
    private $queue = 'https://sqs.us-east-2.amazonaws.com/123456789012/invoice';

    private $connectionName = 'connection-name';

    public function testRelease()
    {
        $message = new Message([
            'ReceiptHandle' => '123',
        ]);

        $sqs = $this->getMockBuilder(SqsClient::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['changeMessageVisibility'])
            ->getMock();

        $sqs->expects(self::once())
            ->method('changeMessageVisibility')
            ->with(self::callback(function (array $input) {
                if ($input['QueueUrl'] !== $this->queue) {
                    return false;
                }

                if (0 !== $input['VisibilityTimeout']) {
                    return false;
                }

                if ('123' !== $input['ReceiptHandle']) {
                    return false;
                }

                return true;
            }));

        $job = new AsyncAwsSqsJob($this->getContainer(), $sqs, $message, $this->connectionName, $this->queue);
        $job->release();
    }

    public function testDelete()
    {
        $message = new Message([
            'ReceiptHandle' => '123',
        ]);

        $sqs = $this->getMockBuilder(SqsClient::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['deleteMessage'])
            ->getMock();

        $sqs->expects(self::once())
            ->method('deleteMessage')
            ->with(self::callback(function (array $input) {
                if ($input['QueueUrl'] !== $this->queue) {
                    return false;
                }

                if ('123' !== $input['ReceiptHandle']) {
                    return false;
                }

                return true;
            }));

        $job = new AsyncAwsSqsJob($this->getContainer(), $sqs, $message, $this->connectionName, $this->queue);
        $job->delete();
    }

    public function testAttempts()
    {
        $message = new Message([
            'Attributes' => ['ApproximateReceiveCount' => '3'],
        ]);

        $job = new AsyncAwsSqsJob($this->getContainer(), $this->getSqs(), $message, $this->connectionName, $this->queue);
        $output = $job->attempts();

        self::assertEquals(3, $output);
    }

    public function testGetJobId()
    {
        $message = new Message([
            'MessageId' => 'message-id-example',
        ]);

        $job = new AsyncAwsSqsJob($this->getContainer(), $this->getSqs(), $message, $this->connectionName, $this->queue);
        $output = $job->getJobId();

        self::assertEquals('message-id-example', $output);
    }

    public function testGetRawBody()
    {
        $message = new Message([
            'Body' => 'super raw body',
        ]);

        $job = new AsyncAwsSqsJob($this->getContainer(), $this->getSqs(), $message, $this->connectionName, $this->queue);
        $output = $job->getRawBody();

        self::assertEquals('super raw body', $output);
    }

    public function testGetSqsJob()
    {
        $data = [
            'Body' => 'super raw body',
            'MessageId' => 'message-id-example',
            'Attributes' => ['ApproximateReceiveCount' => '3'],
            'ReceiptHandle' => '123',
        ];
        $message = new Message($data);

        $job = new AsyncAwsSqsJob($this->getContainer(), $this->getSqs(), $message, $this->connectionName, $this->queue);
        $output = $job->getSqsJob();

        self::assertArrayHasKey('Attributes', $output);
        self::assertArrayHasKey('Body', $output);
        self::assertArrayHasKey('MessageId', $output);
        self::assertArrayHasKey('ReceiptHandle', $output);
        self::assertEquals($data, $output);
    }

    private function getContainer()
    {
        return new Container();
    }

    private function getSqs()
    {
        return $this->getMockBuilder(SqsClient::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
