<?php

namespace AsyncAws\Kms\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kms\Result\GetPublicKeyResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class GetPublicKeyResponseTest extends TestCase
{
    public function testGetPublicKeyResponse(): void
    {
        // see example-1.json from SDK
        $response = new SimpleMockedResponse('{
            "CustomerMasterKeySpec": "RSA_4096",
            "EncryptionAlgorithms": [
                "RSAES_OAEP_SHA_1",
                "RSAES_OAEP_SHA_256"
            ],
            "SigningAlgorithms": [
                "RSASSA_PSS_SHA_384",
                "ECDSA_SHA_256"
            ],
            "KeyAgreementAlgorithms": [
                "ECDH"
            ],            
            "KeyId": "arn:aws:kms:us-west-2:111122223333:key\\/0987dcba-09fe-87dc-65ba-ab0987654321",
            "KeyUsage": "ENCRYPT_DECRYPT",
            "KeySpec": "RSA_4096",
            "PublicKey": "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAyP/BlKX04RLmV8Q2VTSgTpJloxi6MBa1KQ3PMpelcRK4qbTX2+KLw1fiRWsoihvKyVDjvCoehSiv3gnHqzXomMYk/9BxZlJV3R7bDCaeNbjgTem3Qgoxe/0MhYjGoGpNkryJnSJXy03Mx8WNA05ZYErvpt5YATaw7wQtJ+Mei6I/OxemaTQ6QqE5ulQ8wYeF96f4me6RYVuMHb5XcYkA1BMv+two3YCxvQ445EsE+HjExGvNL6Ot+g4yS+cwfcWIcU1kdVTTjXxQVqwQ3kRGb3G934XSyl5BeT7ItbTFNZGJVTNq3GAa3mAQv3BMXPsrhhqcZ/u+F+t2VKe93hw1+QIDAQAB"
        }');

        $client = new MockHttpClient($response);
        $result = new GetPublicKeyResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('arn:aws:kms:us-west-2:111122223333:key/0987dcba-09fe-87dc-65ba-ab0987654321', $result->getKeyId());
        self::assertSame('MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAyP/BlKX04RLmV8Q2VTSgTpJloxi6MBa1KQ3PMpelcRK4qbTX2+KLw1fiRWsoihvKyVDjvCoehSiv3gnHqzXomMYk/9BxZlJV3R7bDCaeNbjgTem3Qgoxe/0MhYjGoGpNkryJnSJXy03Mx8WNA05ZYErvpt5YATaw7wQtJ+Mei6I/OxemaTQ6QqE5ulQ8wYeF96f4me6RYVuMHb5XcYkA1BMv+two3YCxvQ445EsE+HjExGvNL6Ot+g4yS+cwfcWIcU1kdVTTjXxQVqwQ3kRGb3G934XSyl5BeT7ItbTFNZGJVTNq3GAa3mAQv3BMXPsrhhqcZ/u+F+t2VKe93hw1+QIDAQAB', base64_encode($result->getPublicKey()));
        self::assertSame('RSA_4096', $result->getKeySpec());
        self::assertSame('ENCRYPT_DECRYPT', $result->getKeyUsage());
        self::assertContains('RSAES_OAEP_SHA_1', $result->getEncryptionAlgorithms());
        self::assertContains('RSAES_OAEP_SHA_256', $result->getEncryptionAlgorithms());
        self::assertContains('RSASSA_PSS_SHA_384', $result->getSigningAlgorithms());
        self::assertContains('ECDSA_SHA_256', $result->getSigningAlgorithms());
        self::assertContains('ECDH', $result->getKeyAgreementAlgorithms());
    }
}
