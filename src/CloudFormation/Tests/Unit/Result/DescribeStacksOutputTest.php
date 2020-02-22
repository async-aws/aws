<?php

namespace AsyncAws\CloudFormation\Tests\Unit\Result;

use AsyncAws\CloudFormation\Result\DescribeStacksOutput;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

class DescribeStacksOutputTest extends TestCase
{
    public function testDescribeStacksOutput(): void
    {
        self::markTestIncomplete('Not implemented');

        $response = new SimpleMockedResponse('<?xml version="1.0" encoding="UTF-8"?>
            <ChangeIt/>
        ');

        $result = new DescribeStacksOutput($response, new MockHttpClient());

        // self::assertTODO(expected, $result->getStacks());
        self::assertStringContainsString('change it', $result->getNextToken());
    }
}
