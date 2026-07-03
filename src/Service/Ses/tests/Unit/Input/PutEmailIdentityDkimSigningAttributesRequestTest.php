<?php

namespace AsyncAws\Ses\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Ses\Input\PutEmailIdentityDkimSigningAttributesRequest;
use AsyncAws\Ses\ValueObject\DkimSigningAttributes;

class PutEmailIdentityDkimSigningAttributesRequestTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new PutEmailIdentityDkimSigningAttributesRequest([
            'EmailIdentity' => 'change me',
            'SigningAttributesOrigin' => 'change me',
            'SigningAttributes' => new DkimSigningAttributes([
                'DomainSigningSelector' => 'change me',
                'DomainSigningPrivateKey' => 'change me',
                'NextSigningKeyLength' => 'change me',
                'DomainSigningAttributesOrigin' => 'change me',
            ]),
        ]);

        // see https://docs.aws.amazon.com/ses/latest/APIReference-V2/API_PutEmailIdentityDkimSigningAttributes.html
        $expected = '
            PUT / HTTP/1.0
            Content-Type: application/json

            {
            "change": "it"
        }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
