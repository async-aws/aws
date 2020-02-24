<?php

namespace AsyncAws\Core\Tests\Unit\Result;

use AsyncAws\Core\Sts\Result\GetCallerIdentityResponse;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

class GetCallerIdentityResponseTest extends TestCase
{
    public function testGetCallerIdentityResponse(): void
    {
        /** @see https://docs.aws.amazon.com/STS/latest/APIReference/API_GetCallerIdentity.html */
        $response = new SimpleMockedResponse('<?xml version="1.0" encoding="UTF-8"?>
            <GetCallerIdentityResponse xmlns="https://sts.amazonaws.com/doc/2011-06-15/">
                <GetCallerIdentityResult>
                    <Arn>arn:aws:sts::123456789012:assumed-role/my-role-name/my-role-session-name</Arn>
                    <UserId>ARO123EXAMPLE123:my-role-session-name</UserId>
                    <Account>123456789012</Account>
                </GetCallerIdentityResult>
                <ResponseMetadata>
                    <RequestId>01234567-89ab-cdef-0123-456789abcdef</RequestId>
                </ResponseMetadata>
            </GetCallerIdentityResponse>
        ');

        $client = new MockHttpClient($response);
        $result = new GetCallerIdentityResponse($client->request('POST', 'http://localhost'), $client);

        self::assertStringContainsString('ARO123EXAMPLE123:my-role-session-name', $result->getUserId());
        self::assertStringContainsString('123456789012', $result->getAccount());
        self::assertStringContainsString('arn:aws:sts::123456789012:assumed-role/my-role-name/my-role-session-name', $result->getArn());
    }
}
