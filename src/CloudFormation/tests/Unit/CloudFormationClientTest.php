<?php

namespace AsyncAws\CloudFormation\Tests\Unit;

use AsyncAws\CloudFormation\CloudFormationClient;
use AsyncAws\CloudFormation\Input\DescribeStackEventsInput;
use AsyncAws\CloudFormation\Input\DescribeStacksInput;
use AsyncAws\CloudFormation\Result\DescribeStackEventsOutput;
use AsyncAws\CloudFormation\Result\DescribeStacksOutput;
use AsyncAws\Core\Credentials\NullProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

class CloudFormationClientTest extends TestCase
{
    public function testDescribeStackEvents(): void
    {
        $client = new CloudFormationClient([], new NullProvider(), new MockHttpClient());

        $input = new DescribeStackEventsInput([

        ]);
        $result = $client->DescribeStackEvents($input);

        self::assertInstanceOf(DescribeStackEventsOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testDescribeStacks(): void
    {
        $client = new CloudFormationClient([], new NullProvider(), new MockHttpClient());

        $input = new DescribeStacksInput([

        ]);
        $result = $client->DescribeStacks($input);

        self::assertInstanceOf(DescribeStacksOutput::class, $result);
        self::assertFalse($result->info()['resolved']);
    }
}
