<?php

namespace AsyncAws\Core\Tests\Unit;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Sts\Input\AssumeRoleRequest;
use AsyncAws\Core\Sts\Input\GetCallerIdentityRequest;
use AsyncAws\Core\Sts\Result\AssumeRoleResponse;
use AsyncAws\Core\Sts\Result\GetCallerIdentityResponse;
use AsyncAws\Core\Sts\StsClient;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;

class StsClientTest extends TestCase
{
    public function testAssumeRole(): void
    {
        $client = new StsClient([], new NullProvider(), new MockHttpClient());

        $input = new AssumeRoleRequest([
            'RoleArn' => 'change me',
            'RoleSessionName' => 'change me',

        ]);
        $result = $client->AssumeRole($input);

        self::assertInstanceOf(AssumeRoleResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testGetCallerIdentity(): void
    {
        $client = new StsClient([], new NullProvider(), new MockHttpClient());

        $input = new GetCallerIdentityRequest([

        ]);
        $result = $client->GetCallerIdentity($input);

        self::assertInstanceOf(GetCallerIdentityResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }
}
