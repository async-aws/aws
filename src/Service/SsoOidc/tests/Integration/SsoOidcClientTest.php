<?php

namespace AsyncAws\SsoOidc\Tests\Integration;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\SsoOidc\Input\CreateTokenRequest;
use AsyncAws\SsoOidc\Input\RegisterClientRequest;
use AsyncAws\SsoOidc\Input\StartDeviceAuthorizationRequest;
use AsyncAws\SsoOidc\SsoOidcClient;

class SsoOidcClientTest extends TestCase
{
    public function testCreateToken(): void
    {
        $client = $this->getClient();

        $input = new CreateTokenRequest([
            'clientId' => '_yzkThXVzLWVhc3QtMQEXAMPLECLIENTID',
            'clientSecret' => 'VERYLONGSECRETeyJraWQiOiJrZXktMTU2NDAyODA5OSIsImFsZyI6IkhTMzg0In0',
            'grantType' => 'urn:ietf:params:oauth:grant-type:device_code',
            'deviceCode' => 'yJraWQiOiJrZXktMTU2Njk2ODA4OCIsImFsZyI6IkhTMzIn0EXAMPLEDEVICECODE',
            'code' => 'EXAMPLEAUTHORIZATIONCODE',
            'refreshToken' => 'aorvJYubGpU5Dhgwfd2EXAMPLEREFRESHTOKEN',
            'scope' => ['sso:account:access'],
            'redirectUri' => 'http://127.0.0.1:8080/oauth/callback',
            'codeVerifier' => 'dBjftJeZ4CVP-mB92K27uhbUJU1p1r_wW1gFWFOEjXk',
        ]);
        $result = $client->createToken($input);

        $result->resolve();

        self::assertSame('aoak-EXAMPLEACCESSTOKENeyJlbmMiOiJBMjU2R0NNIn0', $result->getAccessToken());
        self::assertSame('Bearer', $result->getTokenType());
        self::assertSame(3600, $result->getExpiresIn());
        self::assertSame('aorvJYubGpU5Dhgwfd2EXAMPLEREFRESHTOKEN', $result->getRefreshToken());
        self::assertSame('eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9EXAMPLEIDTOKEN', $result->getIdToken());
    }

    public function testRegisterClient(): void
    {
        $client = $this->getClient();

        $input = new RegisterClientRequest([
            'clientName' => 'My IDE Plugin',
            'clientType' => 'public',
            'scopes' => ['sso:account:access', 'codewhisperer:completions'],
            'redirectUris' => ['127.0.0.1:PORT/oauth/callback'],
            'grantTypes' => ['authorization_code', 'refresh_token'],
            'issuerUrl' => 'https://identitycenter.amazonaws.com/ssoins-1111111111111111',
            'entitledApplicationArn' => 'arn:aws:sso::ACCOUNTID:application/ssoins-1111111111111111/apl-1111111111111111',
        ]);
        $result = $client->registerClient($input);

        $result->resolve();

        self::assertSame('_yzkThXVzLWVhc3QtMQEXAMPLECLIENTID', $result->getClientId());
        self::assertSame('VERYLONGSECRETeyJraWQiOiJrZXktMTU2NDAyODA5OSIsImFsZyI6IkhTMzg0In0', $result->getClientSecret());
        self::assertSame(1579725929, $result->getClientIdIssuedAt());
        self::assertSame(1587584729, $result->getClientSecretExpiresAt());
        self::assertSame('https://oidc.us-east-1.amazonaws.com/authorize', $result->getAuthorizationEndpoint());
        self::assertSame('https://oidc.us-east-1.amazonaws.com/token', $result->getTokenEndpoint());
    }

    public function testStartDeviceAuthorization(): void
    {
        $client = $this->getClient();

        $input = new StartDeviceAuthorizationRequest([
            'clientId' => '_yzkThXVzLWVhc3QtMQEXAMPLECLIENTID',
            'clientSecret' => 'VERYLONGSECRETeyJraWQiOiJrZXktMTU2NDAyODA5OSIsImFsZyI6IkhTMzg0In0',
            'startUrl' => 'https://directory-alias-example.awsapps.com/start',
        ]);
        $result = $client->startDeviceAuthorization($input);

        $result->resolve();

        self::assertSame('yJraWQiOiJrZXktMTU2Njk2ODA4OCIsImFsZyI6IkhTMzIn0EXAMPLEDEVICECODE', $result->getDeviceCode());
        self::assertSame('makdfsk83yJraWQiOiJrZXktMTU2Njk2sImFsZyI6IkhTMzIn0EXAMPLEUSERCODE', $result->getUserCode());
        self::assertSame('https://directory-alias-example.awsapps.com/start/#/device', $result->getVerificationUri());
        self::assertSame('https://directory-alias-example.awsapps.com/start/#/device?user_code=makdfsk83yJraWQiOiJrZXktMTU2Njk2sImFsZyI6IkhTMzIn0EXAMPLEUSERCODE', $result->getVerificationUriComplete());
        self::assertSame(1579729529, $result->getExpiresIn());
        self::assertSame(1, $result->getInterval());
    }

    private function getClient(): SsoOidcClient
    {
        self::markTestSkipped('There is no image available for a SSO provider mock.');

        return new SsoOidcClient([
            'endpoint' => 'http://localhost',
        ], new NullProvider());
    }
}
