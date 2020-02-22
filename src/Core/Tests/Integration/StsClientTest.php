<?php

namespace AsyncAws\Core\Tests\Integration;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Sts\Input\AssumeRoleRequest;
use AsyncAws\Core\Sts\Input\GetCallerIdentityRequest;
use AsyncAws\Core\Sts\Input\PolicyDescriptorType;
use AsyncAws\Core\Sts\Input\Tag;
use AsyncAws\Core\Sts\StsClient;
use PHPUnit\Framework\TestCase;

class StsClientTest extends TestCase
{
    public function testAssumeRole(): void
    {
        self::markTestIncomplete('Not implemented');

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

    public function testGetCallerIdentity(): void
    {
        self::markTestIncomplete('Not implemented');

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
        return new StsClient([
            'endpoint' => 'http://localhost',
        ], new NullProvider());
    }
}
