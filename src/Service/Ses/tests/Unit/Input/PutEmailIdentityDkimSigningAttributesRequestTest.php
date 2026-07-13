<?php

namespace AsyncAws\Ses\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Ses\Enum\DkimSigningAttributesOrigin;
use AsyncAws\Ses\Enum\DkimSigningKeyLength;
use AsyncAws\Ses\Input\PutEmailIdentityDkimSigningAttributesRequest;
use AsyncAws\Ses\ValueObject\DkimSigningAttributes;

class PutEmailIdentityDkimSigningAttributesRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new PutEmailIdentityDkimSigningAttributesRequest([
            'EmailIdentity' => 'example.com',
            'SigningAttributesOrigin' => DkimSigningAttributesOrigin::EXTERNAL,
            'SigningAttributes' => new DkimSigningAttributes([
                'DomainSigningSelector' => 'selector1',
                'DomainSigningPrivateKey' => 'MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcw',
                'NextSigningKeyLength' => DkimSigningKeyLength::RSA_2048_BIT,
                'DomainSigningAttributesOrigin' => DkimSigningAttributesOrigin::EXTERNAL,
            ]),
        ]);

        // see https://docs.aws.amazon.com/ses/latest/APIReference-V2/API_PutEmailIdentityDkimSigningAttributes.html
        $expected = '
            PUT /v2/email/identities/example.com/dkim/signing HTTP/1.1
            Content-Type: application/json
            Accept: application/json

            {
                "SigningAttributesOrigin": "EXTERNAL",
                "SigningAttributes": {
                    "DomainSigningSelector": "selector1",
                    "DomainSigningPrivateKey": "MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcw",
                    "NextSigningKeyLength": "RSA_2048_BIT",
                    "DomainSigningAttributesOrigin": "EXTERNAL"
                }
            }
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
