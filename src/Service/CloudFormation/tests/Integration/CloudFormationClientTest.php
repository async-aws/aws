<?php

namespace AsyncAws\CloudFormation\Tests\Integration;

use AsyncAws\CloudFormation\CloudFormationClient;
use AsyncAws\CloudFormation\Input\DescribeStackEventsInput;
use AsyncAws\CloudFormation\Input\DescribeStacksInput;
use AsyncAws\Core\Credentials\Credentials;
use AsyncAws\Core\Exception\Http\ClientException;
use PHPUnit\Framework\TestCase;

class CloudFormationClientTest extends TestCase
{
    public function testDescribeStackEvents(): void
    {
        $client = $this->getClient();

        $input = new DescribeStackEventsInput([
            'StackName' => 'demo',
        ]);
        $result = $client->DescribeStackEvents($input);

        self::assertCount(0, $result->getStackEvents());
        self::assertNull($result->getNextToken());
    }

    public function testDescribeStacks(): void
    {
        $client = $this->getClient();

        $input = new DescribeStacksInput([
            'StackName' => 'demo',
        ]);
        $result = $client->DescribeStacks($input);

        self::expectException(ClientException::class);
        self::expectExceptionMessageMatches('/Stack with id demo does not exist/');

        $result->resolve();
    }

    private function getClient(): CloudFormationClient
    {
        return new CloudFormationClient([
            'endpoint' => 'http://localhost:4567',
        ], new Credentials('aws_id', 'aws_secret'));
    }
}
