<?php

namespace AsyncAws\CognitoIdentityProvider\Tests\Unit\Input;

use AsyncAws\CognitoIdentityProvider\Input\SetUserMFAPreferenceRequest;
use AsyncAws\CognitoIdentityProvider\ValueObject\SMSMfaSettingsType;
use AsyncAws\CognitoIdentityProvider\ValueObject\SoftwareTokenMfaSettingsType;
use AsyncAws\Core\Test\TestCase;

class SetUserMFAPreferenceRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new SetUserMFAPreferenceRequest([
            'SMSMfaSettings' => new SMSMfaSettingsType([
                'Enabled' => false,
                'PreferredMfa' => false,
            ]),
            'SoftwareTokenMfaSettings' => new SoftwareTokenMfaSettingsType([
                'Enabled' => false,
                'PreferredMfa' => false,
            ]),
            'AccessToken' => 'B85A977AE91F811E8B1577CCA22C8',
        ]);

        // see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_SetUserMFAPreference.html
        $expected = '
                POST / HTTP/1.0
                Content-Type: application/x-amz-json-1.1
                X-AMZ-Target: AWSCognitoIdentityProviderService.SetUserMFAPreference

                {
                    "SMSMfaSettings": {
                        "Enabled": false,
                        "PreferredMfa": false
                    },
                    "SoftwareTokenMfaSettings": {
                        "Enabled": false,
                        "PreferredMfa": false
                    },
                    "AccessToken": "B85A977AE91F811E8B1577CCA22C8"
                }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
