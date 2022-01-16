<?php

namespace AsyncAws\Kms\Tests\Unit;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kms\Input\DecryptRequest;
use AsyncAws\Kms\Input\EncryptRequest;
use AsyncAws\Kms\Input\GenerateDataKeyRequest;
use AsyncAws\Kms\KmsClient;
use AsyncAws\Kms\Result\DecryptResponse;
use AsyncAws\Kms\Result\EncryptResponse;
use AsyncAws\Kms\Result\GenerateDataKeyResponse;
use Symfony\Component\HttpClient\MockHttpClient;

class KmsClientTest extends TestCase
{
    public function testDecrypt(): void
    {
        $client = new KmsClient([], new NullProvider(), new MockHttpClient());

        $input = new DecryptRequest([
            'CiphertextBlob' => 'change me',

        ]);
        $result = $client->decrypt($input);

        self::assertInstanceOf(DecryptResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testEncrypt(): void
    {
        $client = new KmsClient([], new NullProvider(), new MockHttpClient());

        $input = new EncryptRequest([
            'KeyId' => 'change me',
            'Plaintext' => 'change me',

        ]);
        $result = $client->encrypt($input);

        self::assertInstanceOf(EncryptResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testGenerateDataKey(): void
    {
        $client = new KmsClient([], new NullProvider(), new MockHttpClient());

        $input = new GenerateDataKeyRequest([
            'KeyId' => 'change me',

        ]);
        $result = $client->generateDataKey($input);

        self::assertInstanceOf(GenerateDataKeyResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }
}
