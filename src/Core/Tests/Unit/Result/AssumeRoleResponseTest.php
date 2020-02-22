<?php

namespace AsyncAws\Core\Tests\Unit\Result;

use AsyncAws\Core\Sts\Result\AssumeRoleResponse;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

class AssumeRoleResponseTest extends TestCase
{
    public function testAssumeRoleResponse(): void
    {
        self::markTestIncomplete('Not implemented');

        $response = new SimpleMockedResponse('<?xml version="1.0" encoding="UTF-8"?>
            <ChangeIt/>
        ');

        $result = new AssumeRoleResponse($response, new MockHttpClient());

        // self::assertTODO(expected, $result->getCredentials());
        // self::assertTODO(expected, $result->getAssumedRoleUser());
        self::assertSame(1337, $result->getPackedPolicySize());
    }
}
