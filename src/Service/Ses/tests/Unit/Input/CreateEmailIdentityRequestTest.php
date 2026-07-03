<?php

namespace AsyncAws\Ses\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Ses\Enum\DkimSigningAttributesOrigin;
use AsyncAws\Ses\Enum\DkimSigningKeyLength;
use AsyncAws\Ses\Input\CreateEmailIdentityRequest;
use AsyncAws\Ses\ValueObject\DkimSigningAttributes;
use AsyncAws\Ses\ValueObject\Tag;

class CreateEmailIdentityRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new CreateEmailIdentityRequest([
            'EmailIdentity' => 'example.com',
            'Tags' => [new Tag([
                'Key' => 'Owner',
                'Value' => 'async-aws',
            ])],
            'DkimSigningAttributes' => new DkimSigningAttributes([
                'DomainSigningSelector' => 'selector1',
                'DomainSigningPrivateKey' => 'MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcw',
                'NextSigningKeyLength' => DkimSigningKeyLength::RSA_2048_BIT,
                'DomainSigningAttributesOrigin' => DkimSigningAttributesOrigin::EXTERNAL,
            ]),
            'ConfigurationSetName' => 'my-configuration-set',
        ]);

        // see https://docs.aws.amazon.com/ses/latest/APIReference-V2/API_CreateEmailIdentity.html
        $expected = '
            POST /v2/email/identities HTTP/1.1
            Content-Type: application/json
            Accept: application/json

            {
                "EmailIdentity": "example.com",
                "Tags": [
                    {
                        "Key": "Owner",
                        "Value": "async-aws"
                    }
                ],
                "DkimSigningAttributes": {
                    "DomainSigningSelector": "selector1",
                    "DomainSigningPrivateKey": "MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcw",
                    "NextSigningKeyLength": "RSA_2048_BIT",
                    "DomainSigningAttributesOrigin": "EXTERNAL"
                },
                "ConfigurationSetName": "my-configuration-set"
            }
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
