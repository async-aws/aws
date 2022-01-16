<?php

namespace AsyncAws\Kms\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kms\Result\GenerateDataKeyResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class GenerateDataKeyResponseTest extends TestCase
{
    public function testGenerateDataKeyResponse(): void
    {
        $response = new SimpleMockedResponse('{
            "CiphertextBlob": "PGJpbmFyeSBjaXBoZXJ0ZXh0Pg==",
            "KeyId": "arn:aws:kms:us-east-2:111122223333:key\\/1234abcd-12ab-34cd-56ef-1234567890ab",
            "Plaintext": "PGJpbmFyeSBwYWludGV4dD4="
        }');

        $client = new MockHttpClient($response);
        $result = new GenerateDataKeyResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('<binary ciphertext>', $result->getCiphertextBlob());
        self::assertSame('<binary paintext>', $result->getPlaintext());
        self::assertSame('arn:aws:kms:us-east-2:111122223333:key/1234abcd-12ab-34cd-56ef-1234567890ab', $result->getKeyId());
    }
}
