<?php

namespace AsyncAws\S3\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\S3\Enum\ServerSideEncryption;
use AsyncAws\S3\Result\GetBucketEncryptionOutput;
use AsyncAws\S3\ValueObject\ServerSideEncryptionByDefault;
use AsyncAws\S3\ValueObject\ServerSideEncryptionConfiguration;
use AsyncAws\S3\ValueObject\ServerSideEncryptionRule;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class GetBucketEncryptionOutputTest extends TestCase
{
    public function testGetBucketEncryptionOutput(): void
    {
        // see https://docs.aws.amazon.com/s3/latest/APIReference/API_GetBucketEncryption.html
        $response = new SimpleMockedResponse('
<ServerSideEncryptionConfiguration xmlns="http://s3.amazonaws.com/doc/2006-03-01/">
    <Rule>
        <ApplyServerSideEncryptionByDefault>
            <KMSMasterKeyID>arn:aws:kms:us-east-1:1234abcd-12ab-34cd-56ef-1234567890ab</KMSMasterKeyID>
            <SSEAlgorithm>aws:kms</SSEAlgorithm>
        </ApplyServerSideEncryptionByDefault>
        <BucketKeyEnabled>boolean</BucketKeyEnabled>
    </Rule>
</ServerSideEncryptionConfiguration>');

        $client = new MockHttpClient($response);
        $result = new GetBucketEncryptionOutput(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        $sseConfig = $result->getServerSideEncryptionConfiguration();

        self::assertInstanceOf(ServerSideEncryptionConfiguration::class, $sseConfig);
        self::assertCount(1, $sseConfig->getRules());

        $firstRule = $sseConfig->getRules()[0];

        self::assertInstanceOf(ServerSideEncryptionRule::class, $firstRule);
        self::assertInstanceOf(ServerSideEncryptionByDefault::class, $firstRule->getApplyServerSideEncryptionByDefault());
        self::assertSame('arn:aws:kms:us-east-1:1234abcd-12ab-34cd-56ef-1234567890ab', $firstRule->getApplyServerSideEncryptionByDefault()->getKmsMasterKeyId());
        self::assertSame(ServerSideEncryption::AWS_KMS, $firstRule->getApplyServerSideEncryptionByDefault()->getSseAlgorithm());
        self::assertFalse($firstRule->getBucketKeyEnabled());
    }
}
