<?php

namespace AsyncAws\Ses\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Ses\Result\GetEmailIdentityResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class GetEmailIdentityResponseTest extends TestCase
{
    public function testGetEmailIdentityResponse(): void
    {
        // see https://docs.aws.amazon.com/ses/latest/APIReference-V2/API_GetEmailIdentity.html
        $response = new SimpleMockedResponse('{
            "IdentityType": "DOMAIN",
            "FeedbackForwardingStatus": true,
            "VerifiedForSendingStatus": true,
            "DkimAttributes": {
                "SigningEnabled": true,
                "Status": "SUCCESS",
                "Tokens": ["token1", "token2", "token3"],
                "SigningHostedZone": "us-east-1.ses.example.aws",
                "SigningAttributesOrigin": "AWS_SES",
                "NextSigningKeyLength": "RSA_2048_BIT",
                "CurrentSigningKeyLength": "RSA_1024_BIT",
                "LastKeyGenerationTimestamp": 1622505600
            },
            "MailFromAttributes": {
                "MailFromDomain": "mail.example.com",
                "MailFromDomainStatus": "SUCCESS",
                "BehaviorOnMxFailure": "USE_DEFAULT_VALUE"
            },
            "Policies": {
                "MyPolicy": "{\"Version\":\"2012-10-17\"}"
            },
            "Tags": [
                {"Key": "Owner", "Value": "async-aws"}
            ],
            "ConfigurationSetName": "my-configuration-set",
            "VerificationStatus": "SUCCESS",
            "VerificationInfo": {
                "LastCheckedTimestamp": 1622505600,
                "LastSuccessTimestamp": 1622505600,
                "ErrorType": "DNS_SERVER_ERROR",
                "SOARecord": {
                    "PrimaryNameServer": "ns1.example.com",
                    "AdminEmail": "admin.example.com",
                    "SerialNumber": 123456789
                }
            }
        }');

        $client = new MockHttpClient($response);
        $result = new GetEmailIdentityResponse(new Response($client->request('GET', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('DOMAIN', $result->getIdentityType());
        self::assertTrue($result->getFeedbackForwardingStatus());
        self::assertTrue($result->getVerifiedForSendingStatus());

        $dkimAttributes = $result->getDkimAttributes();
        self::assertTrue($dkimAttributes->getSigningEnabled());
        self::assertSame('SUCCESS', $dkimAttributes->getStatus());
        self::assertSame(['token1', 'token2', 'token3'], $dkimAttributes->getTokens());
        self::assertSame('us-east-1.ses.example.aws', $dkimAttributes->getSigningHostedZone());
        self::assertSame('AWS_SES', $dkimAttributes->getSigningAttributesOrigin());
        self::assertSame('RSA_2048_BIT', $dkimAttributes->getNextSigningKeyLength());
        self::assertSame('RSA_1024_BIT', $dkimAttributes->getCurrentSigningKeyLength());
        self::assertSame(1622505600, $dkimAttributes->getLastKeyGenerationTimestamp()->getTimestamp());

        $mailFromAttributes = $result->getMailFromAttributes();
        self::assertSame('mail.example.com', $mailFromAttributes->getMailFromDomain());
        self::assertSame('SUCCESS', $mailFromAttributes->getMailFromDomainStatus());
        self::assertSame('USE_DEFAULT_VALUE', $mailFromAttributes->getBehaviorOnMxFailure());

        self::assertSame(['MyPolicy' => '{"Version":"2012-10-17"}'], $result->getPolicies());

        $tags = $result->getTags();
        self::assertCount(1, $tags);
        self::assertSame('Owner', $tags[0]->getKey());
        self::assertSame('async-aws', $tags[0]->getValue());

        self::assertSame('my-configuration-set', $result->getConfigurationSetName());
        self::assertSame('SUCCESS', $result->getVerificationStatus());

        $verificationInfo = $result->getVerificationInfo();
        self::assertSame(1622505600, $verificationInfo->getLastCheckedTimestamp()->getTimestamp());
        self::assertSame(1622505600, $verificationInfo->getLastSuccessTimestamp()->getTimestamp());
        self::assertSame('DNS_SERVER_ERROR', $verificationInfo->getErrorType());

        $soaRecord = $verificationInfo->getSoaRecord();
        self::assertSame('ns1.example.com', $soaRecord->getPrimaryNameServer());
        self::assertSame('admin.example.com', $soaRecord->getAdminEmail());
        self::assertSame(123456789, $soaRecord->getSerialNumber());
    }
}
