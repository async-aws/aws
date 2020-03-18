<?php

namespace AsyncAws\Core\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Sts\Result\AssumeRoleWithWebIdentityResponse;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

class AssumeRoleWithWebIdentityResponseTest extends TestCase
{
    public function testAssumeRoleWithWebIdentityResponse(): void
    {
        // see https://docs.aws.amazon.com/STS/latest/APIReference/API_AssumeRoleWithWebIdentity.html
        $response = new SimpleMockedResponse('<AssumeRoleWithWebIdentityResponse xmlns="https://sts.amazonaws.com/doc/2011-06-15/">
            <AssumeRoleWithWebIdentityResult>
                <SubjectFromWebIdentityToken>amzn1.account.AF6RHO7KZU5XRVQJGXK6HB56KR2A</SubjectFromWebIdentityToken>
                <Audience>client.5498841531868486423.1548@apps.example.com</Audience>
                <AssumedRoleUser>
                    <Arn>arn:aws:sts::123456789012:assumed-role/FederatedWebIdentityRole/app1</Arn>
                    <AssumedRoleId>AROACLKWSDQRAOEXAMPLE:app1</AssumedRoleId>
                </AssumedRoleUser>
                <Credentials>
                    <SessionToken>AQoDYXdzEE0a8ANXXXXXXXXNO1ewxE5TijQyp+IEXAMPLE</SessionToken>
                    <SecretAccessKey>wJalrXUtnFEMI/K7MDENG/bPxRfiCYzEXAMPLEKEY</SecretAccessKey>
                    <Expiration>2014-10-24T23:00:23Z</Expiration>
                    <AccessKeyId>ASgeIAIOSFODNN7EXAMPLE</AccessKeyId>
                </Credentials>
                <Provider>www.amazon.com</Provider>
            </AssumeRoleWithWebIdentityResult>
            <ResponseMetadata>
                <RequestId>ad4156e9-bce1-11e2-82e6-6b6efEXAMPLE</RequestId>
            </ResponseMetadata>
        </AssumeRoleWithWebIdentityResponse>');

        $client = new MockHttpClient($response);
        $result = new AssumeRoleWithWebIdentityResponse(new Response($client->request('POST', 'http://localhost'), $client));

        // self::assertTODO(expected, $result->getCredentials());
        self::assertSame('amzn1.account.AF6RHO7KZU5XRVQJGXK6HB56KR2A', $result->getSubjectFromWebIdentityToken());
        self::assertSame('arn:aws:sts::123456789012:assumed-role/FederatedWebIdentityRole/app1', $result->getAssumedRoleUser()->getArn());
        self::assertSame('AROACLKWSDQRAOEXAMPLE:app1', $result->getAssumedRoleUser()->getAssumedRoleId());
        self::assertSame('AQoDYXdzEE0a8ANXXXXXXXXNO1ewxE5TijQyp+IEXAMPLE', $result->getCredentials()->getSessionToken());
        self::assertSame('wJalrXUtnFEMI/K7MDENG/bPxRfiCYzEXAMPLEKEY', $result->getCredentials()->getSecretAccessKey());
        self::assertEquals(new \DateTimeImmutable('2014-10-24T23:00:23Z'), $result->getCredentials()->getExpiration());
        self::assertSame('ASgeIAIOSFODNN7EXAMPLE', $result->getCredentials()->getAccessKeyId());
        self::assertSame('www.amazon.com', $result->getProvider());
        self::assertSame('client.5498841531868486423.1548@apps.example.com', $result->getAudience());
    }
}
