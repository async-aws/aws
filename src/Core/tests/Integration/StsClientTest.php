<?php

namespace AsyncAws\Core\Tests\Integration;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Sts\Input\AssumeRoleRequest;
use AsyncAws\Core\Sts\Input\AssumeRoleWithWebIdentityRequest;
use AsyncAws\Core\Sts\Input\GetCallerIdentityRequest;
use AsyncAws\Core\Sts\Input\PolicyDescriptorType;
use AsyncAws\Core\Sts\Input\Tag;
use AsyncAws\Core\Sts\StsClient;
use AsyncAws\Core\Test\TestCase;

class StsClientTest extends TestCase
{
    public function testAssumeRole(): void
    {
        $client = $this->getClient();

        $input = new AssumeRoleRequest([
            'RoleArn' => 'change me',
            'RoleSessionName' => 'change me',
            'PolicyArns' => [new PolicyDescriptorType([
                'arn' => 'change me',
            ])],
            'Policy' => 'change me',
            'DurationSeconds' => 1337,
            'Tags' => [new Tag([
                'Key' => 'change me',
                'Value' => 'change me',
            ])],
            'TransitiveTagKeys' => ['change me'],
            'ExternalId' => 'change me',
            'SerialNumber' => 'change me',
            'TokenCode' => 'change me',
        ]);
        $result = $client->AssumeRole($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getCredentials());
        // self::assertTODO(expected, $result->getAssumedRoleUser());
        self::assertSame(1337, $result->getPackedPolicySize());
    }

    public function testAssumeRoleWithWebIdentity(): void
    {
        $client = $this->getClient();

        $input = new AssumeRoleWithWebIdentityRequest([
            'RoleArn' => 'change me',
            'RoleSessionName' => 'change me',
            'WebIdentityToken' => 'change me',
            'ProviderId' => 'change me',
            'PolicyArns' => [new PolicyDescriptorType([
                'arn' => 'change me',
            ])],
            'Policy' => 'change me',
            'DurationSeconds' => 1337,
        ]);
        $result = $client->AssumeRoleWithWebIdentity($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getCredentials());
        self::assertSame('changeIt', $result->getSubjectFromWebIdentityToken());
        // self::assertTODO(expected, $result->getAssumedRoleUser());
        self::assertSame(1337, $result->getPackedPolicySize());
        self::assertSame('changeIt', $result->getProvider());
        self::assertSame('changeIt', $result->getAudience());
    }

    public function testGetCallerIdentity(): void
    {
        $client = $this->getClient();

        $input = new GetCallerIdentityRequest([

        ]);
        $result = $client->GetCallerIdentity($input);

        $result->resolve();

        self::assertStringContainsString('change it', $result->getUserId());
        self::assertStringContainsString('change it', $result->getAccount());
        self::assertStringContainsString('change it', $result->getArn());
    }

    private function getClient(): StsClient
    {
        self::markTestSkipped('No Docker image for STS');

        return new StsClient([
            'endpoint' => 'http://localhost',
        ], new NullProvider());
    }
}
