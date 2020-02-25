<?php

namespace AsyncAws\Sns\Tests\Integration;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Sns\Input\MessageAttributeValue;
use AsyncAws\Sns\Input\PublishInput;
use AsyncAws\Sns\SnsClient;
use PHPUnit\Framework\TestCase;

class SnsClientTest extends TestCase
{
    public function testPublish(): void
    {
        self::markTestSkipped('No Docker image for Sns');

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

    private function getClient(): SnsClient
    {
        return new SnsClient([
            'endpoint' => 'http://localhost',
        ], new NullProvider());
    }
}
