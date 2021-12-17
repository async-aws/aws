<?php

namespace AsyncAws\CloudFormation\Tests\Integration;

use AsyncAws\CloudFormation\CloudFormationClient;
use AsyncAws\CloudFormation\Input\DescribeStackDriftDetectionStatusInput;
use AsyncAws\CloudFormation\Input\DescribeStackEventsInput;
use AsyncAws\CloudFormation\Input\DescribeStacksInput;
use AsyncAws\Core\Credentials\Credentials;
use AsyncAws\Core\Exception\Http\ClientException;
use AsyncAws\Core\Test\TestCase;

class CloudFormationClientTest extends TestCase
{
    public function testDescribeStackDriftDetectionStatus(): void
    {
        self::markTestSkipped('The CloudFormation Docker image does not implement StackDrifts.');

        $client = $this->getClient();

        $input = new DescribeStackDriftDetectionStatusInput([
            'StackDriftDetectionId' => 'b78ac9b0-dec1-11e7-a451-503a3example',
        ]);

        $result = $client->describeStackDriftDetectionStatus($input);

        self::expectException(ClientException::class);

        $result->resolve();

        self::assertSame('changeIt', $result->getStackId());
        self::assertSame('changeIt', $result->getStackDriftDetectionId());
        self::assertSame('changeIt', $result->getStackDriftStatus());
        self::assertSame('changeIt', $result->getDetectionStatus());
        self::assertSame('changeIt', $result->getDetectionStatusReason());
        self::assertSame(1337, $result->getDriftedStackResourceCount());
    }

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
