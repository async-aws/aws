<?php

declare(strict_types=1);

namespace AsyncAws\Sqs\Tests\Integration;

use AsyncAws\Sqs\Input\SendMessageRequest;
use PHPUnit\Framework\TestCase;

class SqsClientTest extends TestCase
{
    use GetClient;

    public function testSendMessage()
    {
        $sqs = $this->getClient();

        $result = $sqs->createQueue(['QueueName' => 'bar']);
        $result->resolve();
        self::assertEquals(200, $result->info()['status']);

        $input = new SendMessageRequest();
        $input
            ->setQueueUrl('https://foo.com/bar')
            ->setMessageBody('foobar');

        $result = $sqs->sendMessage($input);
        self::assertNotNull($result->getMessageId());
    }
}
