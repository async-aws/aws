<?php

namespace AsyncAws\Core\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Sts\Result\AssumeRoleResponse;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

class AssumeRoleResponseTest extends TestCase
{
    public function testAssumeRoleResponse(): void
    {
        $response = new SimpleMockedResponse('<?xml version="1.0" encoding="UTF-8"?>
            <AssumeRoleResponse xmlns="https://sts.amazonaws.com/doc/2011-06-15/">
                <AssumeRoleResult>
                    <AssumedRoleUser>
                        <Arn>arn:aws:sts::123456789012:assumed-role/demo/TestAR</Arn>
                        <AssumedRoleId>ARO123EXAMPLE123:TestAR</AssumedRoleId>
                    </AssumedRoleUser>
                    <Credentials>
                        <AccessKeyId>ASIAIOSFODNN7EXAMPLE</AccessKeyId>
                        <SecretAccessKey>wJalrXUtnFEMI/K7MDENG/bPxRfiCYzEXAMPLEKEY</SecretAccessKey>
                        <SessionToken>
                            AQoDYXdzEPT//////////wEXAMPLEtc764bNrC9SAPBSM22wDOk4x4HIZ8j4FZTwdQW
                            LWsKWHGBuFqwAeMicRXmxfpSPfIeoIYRqTflfKD8YUuwthAx7mSEI/qkPpKPi/kMcGd
                            QrmGdeehM4IC1NtBmUpp2wUE8phUZampKsburEDy0KPkyQDYwT7WZ0wq5VSXDvp75YU
                            9HFvlRd8Tx6q6fE8YQcHNVXAkiY9q6d+xo0rKwT38xVqr7ZD0u0iPPkUL64lIZbqBAz
                            +scqKmlzm8FDrypNC9Yjc8fPOLn9FX9KSYvKTr4rvx3iSIlTJabIQwj2ICCR/oLxBA==
                        </SessionToken>
                        <Expiration>2019-11-09T13:34:41Z</Expiration>
                    </Credentials>
                    <PackedPolicySize>6</PackedPolicySize>
                </AssumeRoleResult>
                <ResponseMetadata>
                    <RequestId>c6104cbe-af31-11e0-8154-cbc7ccf896c7</RequestId>
                </ResponseMetadata>
            </AssumeRoleResponse>
        ');

        $client = new MockHttpClient($response);
        $result = new AssumeRoleResponse(new Response($client->request('POST', 'http://localhost'), $client));

        self::assertSame('arn:aws:sts::123456789012:assumed-role/demo/TestAR', $result->getAssumedRoleUser()->getArn());
        self::assertSame('ARO123EXAMPLE123:TestAR', $result->getAssumedRoleUser()->getAssumedRoleId());
        self::assertSame('ASIAIOSFODNN7EXAMPLE', $result->getCredentials()->getAccessKeyId());
        self::assertSame('wJalrXUtnFEMI/K7MDENG/bPxRfiCYzEXAMPLEKEY', $result->getCredentials()->getSecretAccessKey());
        self::assertStringContainsString('AQoDYXdzEPT', $result->getCredentials()->getSessionToken());
        self::assertSame('20191109', $result->getCredentials()->getExpiration()->format('Ymd'));
        self::assertSame(6, $result->getPackedPolicySize());
    }
}
