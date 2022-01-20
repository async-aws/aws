<?php

namespace AsyncAws\Kms\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kms\Enum\EncryptionAlgorithmSpec;
use AsyncAws\Kms\Enum\KeySpec;
use AsyncAws\Kms\Enum\KeyUsageType;
use AsyncAws\Kms\Result\CreateKeyResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class CreateKeyResponseTest extends TestCase
{
    public function testCreateKeyResponse(): void
    {
        // see example-1.json from SDK
        $response = new SimpleMockedResponse('{
            "KeyMetadata": {
                "AWSAccountId": "111122223333",
                "Arn": "arn:aws:kms:us-east-2:111122223333:key\\/1234abcd-12ab-34cd-56ef-1234567890ab",
                "CreationDate": "2017-07-05T14:04:55-07:00",
                "CustomerMasterKeySpec": "SYMMETRIC_DEFAULT",
                "Description": "",
                "Enabled": true,
                "EncryptionAlgorithms": [
                    "SYMMETRIC_DEFAULT"
                ],
                "KeyId": "1234abcd-12ab-34cd-56ef-1234567890ab",
                "KeyManager": "CUSTOMER",
                "KeySpec": "SYMMETRIC_DEFAULT",
                "KeyState": "Enabled",
                "KeyUsage": "ENCRYPT_DECRYPT",
                "MultiRegion": false,
                "Origin": "AWS_KMS"
            }
        }');

        $client = new MockHttpClient($response);
        $result = new CreateKeyResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('1234abcd-12ab-34cd-56ef-1234567890ab', $result->getKeyMetadata()->getKeyId());
        self::assertSame(KeySpec::SYMMETRIC_DEFAULT, $result->getKeyMetadata()->getKeySpec());
        self::assertSame(KeyUsageType::ENCRYPT_DECRYPT, $result->getKeyMetadata()->getKeyUsage());
        self::assertSame([EncryptionAlgorithmSpec::SYMMETRIC_DEFAULT], $result->getKeyMetadata()->getEncryptionAlgorithms());
    }
}
