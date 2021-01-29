<?php

namespace AsyncAws\Core\Tests\Integration;

use AsyncAws\Core\Credentials\Credentials;
use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Sts\Input\AssumeRoleRequest;
use AsyncAws\Core\Sts\Input\AssumeRoleWithWebIdentityRequest;
use AsyncAws\Core\Sts\Input\GetCallerIdentityRequest;
use AsyncAws\Core\Sts\StsClient;
use AsyncAws\Core\Sts\ValueObject\PolicyDescriptorType;
use AsyncAws\Core\Sts\ValueObject\Tag;
use AsyncAws\Core\Test\TestCase;

class StsClientTest extends TestCase
{
    public function testAssumeRole(): void
    {
        $client = $this->getClient();

        $input = new AssumeRoleRequest([
            'RoleArn' => 'arn:aws::iam::123456789012:role/demo',
            'RoleSessionName' => 'John-session',
            'PolicyArns' => [new PolicyDescriptorType([
                'arn' => 'arn:aws::iam::123456789012:policy/demo',
            ])],
            'Policy' => '{"Version":"2012-10-17","Statement":[{"Sid": "Stmt1","Effect": "Allow","Action": "s3:*","Resource": "*"}]}',
            'DurationSeconds' => 300,
            'Tags' => [new Tag([
                'Key' => 'Project',
                'Value' => 'Pegasus',
            ])],
            'TransitiveTagKeys' => ['Project', 'Cost-Center'],
            'ExternalId' => '123ABC',
            'SerialNumber' => '12345678',
            'TokenCode' => 'change me',
        ]);
        $result = $client->AssumeRole($input);

        self::assertNotNull($result->getCredentials());
        self::assertLessThanOrEqual(new \DateTime('+5min'), $result->getCredentials()->getExpiration());
        self::assertNotNull($result->getAssumedRoleUser());
        self::assertSame('arn:aws:sts::000000000000:assumed-role/demo/John-session', $result->getAssumedRoleUser()->getArn());
        self::assertSame(6, $result->getPackedPolicySize());
    }

    public function testAssumeRoleWithWebIdentity(): void
    {
        $client = $this->getClient();

        $input = new AssumeRoleWithWebIdentityRequest([
            'RoleArn' => 'arn:aws:iam::123456789012:role/FederatedWebIdentityRole',
            'RoleSessionName' => 'app1',
            'WebIdentityToken' => 'FooBarBaz',
            'ProviderId' => 'www.amazon.com',
            'PolicyArns' => [new PolicyDescriptorType([
                'arn' => 'arn:aws:iam::123456789012:policy/q=webidentitydemopolicy1',
            ]), new PolicyDescriptorType([
                'arn' => 'arn:aws:iam::123456789012:policy/webidentitydemopolicy2',
            ])],
            'DurationSeconds' => 300,
        ]);
        $result = $client->AssumeRoleWithWebIdentity($input);

        self::assertNotNull($result->getCredentials());
        self::assertLessThanOrEqual(new \DateTime('+5min'), $result->getCredentials()->getExpiration());
        self::assertNotNull($result->getAssumedRoleUser());
        self::assertSame('arn:aws:sts::000000000000:assumed-role/FederatedWebIdentityRole/app1', $result->getAssumedRoleUser()->getArn());
        self::assertSame(6, $result->getPackedPolicySize());
    }

    public function testGetCallerIdentity(): void
    {
        $client = $this->getClient();

        $input = new GetCallerIdentityRequest();
        $result = $client->GetCallerIdentity($input);

        self::assertNotNull($result->getUserId());
        self::assertStringContainsString('000000000000', $result->getAccount());
        self::assertStringContainsString('arn:aws:sts::000000000000:user/moto', $result->getArn());
    }

    public function testNonAwsRegionWithCustomEndpoint(): void
    {
        $client = new StsClient([
            'endpoint' => 'http://localhost',
            'region' => 'test',
        ], new NullProvider());
        self::assertNotEmpty($client->presign(new AssumeRoleRequest(['RoleArn' => 'demo', 'RoleSessionName' => 'demo'])));
    }

    /**
     * A region that is not recognized should be treated as "default" region.
     */
    public function testNonAwsRegion(): void
    {
        $client = new StsClient([
            'region' => 'test',
        ], new NullProvider());

        self::assertNotEmpty($client->presign(new AssumeRoleRequest(['RoleArn' => 'demo', 'RoleSessionName' => 'demo'])));
    }

    public function testCustomEndpointSignature(): void
    {
        $client = new StsClient([
            'endpoint' => 'https://custom.acme.com',
            'region' => 'demo',
            'accessKeyId' => '123',
            'accessKeySecret' => '123',
        ]);

        $url = $client->presign(new AssumeRoleRequest([
            'RoleArn' => 'test',
            'RoleSessionName' => 'test',
        ]));
        \parse_str(\parse_url($url, \PHP_URL_QUERY), $query);

        self::assertStringContainsString('/demo/', $query['X-Amz-Credential']);
    }

    private function getClient(): StsClient
    {
        return new StsClient([
            'endpoint' => 'http://localhost:4566',
        ], new Credentials('aws_id', 'aws_secret'));
    }
}
