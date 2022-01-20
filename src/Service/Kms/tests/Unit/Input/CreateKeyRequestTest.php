<?php

namespace AsyncAws\Kms\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kms\Enum\KeySpec;
use AsyncAws\Kms\Enum\KeyUsageType;
use AsyncAws\Kms\Enum\OriginType;
use AsyncAws\Kms\Input\CreateKeyRequest;
use AsyncAws\Kms\ValueObject\Tag;

class CreateKeyRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new CreateKeyRequest([
            'Description' => 'My key',
            'KeyUsage' => KeyUsageType::ENCRYPT_DECRYPT,
            'KeySpec' => KeySpec::SYMMETRIC_DEFAULT,
            'Origin' => OriginType::AWS_KMS,
            'Tags' => [new Tag([
                'TagKey' => 'CreatedBy',
                'TagValue' => 'ExampleUser',
            ])],
            'MultiRegion' => false,
        ]);

        // see https://docs.aws.amazon.com/kms/latest/APIReference/API_CreateKey.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            x-amz-target: TrentService.CreateKey

            {
              "Description": "My key",
              "KeySpec": "SYMMETRIC_DEFAULT",
              "KeyUsage": "ENCRYPT_DECRYPT",
              "MultiRegion": false,
              "Origin": "AWS_KMS",
              "Tags": [{
                "TagValue": "ExampleUser",
                "TagKey": "CreatedBy"
              }]
            }
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
