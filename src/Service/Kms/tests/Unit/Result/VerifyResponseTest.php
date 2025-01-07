<?php

namespace AsyncAws\Kms\Tests\Unit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kms\Result\VerifyResponse;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class VerifyResponseTest extends TestCase
{
    public function testVerifyResponse(): void
    {
        // see example-1.json from SDK
        $response = new SimpleMockedResponse('{
            "KeyId": "arn:aws:kms:us-east-2:111122223333:key\\/1234abcd-12ab-34cd-56ef-1234567890ab",
            "SignatureValid": true,
            "SigningAlgorithm": "ECDSA_SHA_384"
        }');

        $client = new MockHttpClient($response);
        $result = new VerifyResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('arn:aws:kms:us-east-2:111122223333:key/1234abcd-12ab-34cd-56ef-1234567890ab', $result->getKeyId());
        self::assertTrue($result->getSignatureValid());
        self::assertSame('ECDSA_SHA_384', $result->getSigningAlgorithm());
    }
}
