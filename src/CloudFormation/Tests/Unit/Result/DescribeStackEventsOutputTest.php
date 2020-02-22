<?php

namespace AsyncAws\CloudFormation\Tests\Unit\Result;

use AsyncAws\CloudFormation\Result\DescribeStackEventsOutput;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

class DescribeStackEventsOutputTest extends TestCase
{
    public function testDescribeStackEventsOutput(): void
    {
        self::markTestIncomplete('Not implemented');

        $response = new SimpleMockedResponse('<?xml version="1.0" encoding="UTF-8"?>
            <ChangeIt/>
        ');

        $result = new DescribeStackEventsOutput($response, new MockHttpClient());

        // self::assertTODO(expected, $result->getStackEvents());
        self::assertStringContainsString('change it', $result->getNextToken());
    }
}
