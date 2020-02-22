<?php

namespace AsyncAws\CloudFormation\Tests\Integration;

use AsyncAws\CloudFormation\CloudFormationClient;
use AsyncAws\CloudFormation\Input\DescribeStackEventsInput;
use AsyncAws\CloudFormation\Input\DescribeStacksInput;
use AsyncAws\Core\Credentials\NullProvider;
use PHPUnit\Framework\TestCase;

class CloudFormationClientTest extends TestCase
{
    public function testDescribeStackEvents(): void
    {
        self::markTestIncomplete('Not implemented');

        $client = $this->getClient();

        $input = new DescribeStackEventsInput([
            'StackName' => 'change me',
            'NextToken' => 'change me',
        ]);
        $result = $client->DescribeStackEvents($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getStackEvents());
        self::assertStringContainsString('change it', $result->getNextToken());
    }

    public function testDescribeStacks(): void
    {
        self::markTestIncomplete('Not implemented');

        $client = $this->getClient();

        $input = new DescribeStacksInput([
            'StackName' => 'change me',
            'NextToken' => 'change me',
        ]);
        $result = $client->DescribeStacks($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getStacks());
        self::assertStringContainsString('change it', $result->getNextToken());
    }

    private function getClient(): CloudFormationClient
    {
        return new CloudFormationClient([
            'endpoint' => 'http://localhost',
        ], new NullProvider());
    }
}
