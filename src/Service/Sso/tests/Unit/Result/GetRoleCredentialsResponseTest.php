<?php

namespace AsyncAws\Sso\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Sso\Result\GetRoleCredentialsResponse;
use AsyncAws\Sso\ValueObject\RoleCredentials;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class GetRoleCredentialsResponseTest extends TestCase
{
    public function testGetRoleCredentialsResponse(): void
    {
        // see https://docs.aws.amazon.com/singlesignon/latest/PortalAPIReference/API_GetRoleCredentials.html
        $response = new SimpleMockedResponse('{
           "roleCredentials": {
              "accessKeyId": "AccessKeyId",
              "expiration": 1689063192,
              "secretAccessKey": "SecretAccessKey",
              "sessionToken": "SessionToken"
           }
        }');

        $client = new MockHttpClient($response);
        $result = new GetRoleCredentialsResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertEquals(
            new RoleCredentials([
                'accessKeyId' => 'AccessKeyId',
                'secretAccessKey' => 'SecretAccessKey',
                'sessionToken' => 'SessionToken',
                'expiration' => 1689063192,
            ]),
            $result->getRoleCredentials()
        );
    }
}
