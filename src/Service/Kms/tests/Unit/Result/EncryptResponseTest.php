<?php

namespace AsyncAws\Kms\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kms\Enum\EncryptionAlgorithmSpec;
use AsyncAws\Kms\Result\EncryptResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class EncryptResponseTest extends TestCase
{
    public function testEncryptResponse(): void
    {
        $response = new SimpleMockedResponse('{
            "CiphertextBlob": "PGJpbmFyeSBkYXRhPg==",
            "KeyId": "arn:aws:kms:us-west-2:111122223333:key\\/1234abcd-12ab-34cd-56ef-1234567890ab",
            "EncryptionAlgorithm": "SYMMETRIC_DEFAULT"
        }');

        $client = new MockHttpClient($response);
        $result = new EncryptResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('<binary data>', $result->getCiphertextBlob());
        self::assertSame('arn:aws:kms:us-west-2:111122223333:key/1234abcd-12ab-34cd-56ef-1234567890ab', $result->getKeyId());
        self::assertSame(EncryptionAlgorithmSpec::SYMMETRIC_DEFAULT, $result->getEncryptionAlgorithm());
    }
}
