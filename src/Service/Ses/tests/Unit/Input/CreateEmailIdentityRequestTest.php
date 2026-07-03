<?php

namespace AsyncAws\Ses\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Ses\Input\CreateEmailIdentityRequest;
use AsyncAws\Ses\ValueObject\DkimSigningAttributes;
use AsyncAws\Ses\ValueObject\Tag;

class CreateEmailIdentityRequestTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new CreateEmailIdentityRequest([
            'EmailIdentity' => 'change me',
            'Tags' => [new Tag([
                'Key' => 'change me',
                'Value' => 'change me',
            ])],
            'DkimSigningAttributes' => new DkimSigningAttributes([
                'DomainSigningSelector' => 'change me',
                'DomainSigningPrivateKey' => 'change me',
                'NextSigningKeyLength' => 'change me',
                'DomainSigningAttributesOrigin' => 'change me',
            ]),
            'ConfigurationSetName' => 'change me',
        ]);

        // see https://docs.aws.amazon.com/ses/latest/APIReference-V2/API_CreateEmailIdentity.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/json

            {
            "change": "it"
        }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
