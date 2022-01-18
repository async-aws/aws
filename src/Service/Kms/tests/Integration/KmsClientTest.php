<?php

namespace AsyncAws\Kms\Tests\Integration;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kms\Input\DecryptRequest;
use AsyncAws\Kms\Input\EncryptRequest;
use AsyncAws\Kms\Input\GenerateDataKeyRequest;
use AsyncAws\Kms\Input\ListAliasesRequest;
use AsyncAws\Kms\KmsClient;

class KmsClientTest extends TestCase
{
    public function testDecrypt(): void
    {
        self::markTestIncomplete('Cannot test Decrypt without the ability to create keys available.');

        $client = $this->getClient();

        $input = new DecryptRequest([
            'CiphertextBlob' => 'change me',
            'EncryptionContext' => ['change me' => 'change me'],
            'GrantTokens' => ['change me'],
            'KeyId' => 'change me',
            'EncryptionAlgorithm' => 'change me',
        ]);
        $result = $client->decrypt($input);

        $result->resolve();

        self::assertSame('changeIt', $result->getKeyId());
        // self::assertTODO(expected, $result->getPlaintext());
        self::assertSame('changeIt', $result->getEncryptionAlgorithm());
    }

    public function testEncrypt(): void
    {
        self::markTestIncomplete('Cannot test Encrypt without the ability to create keys available.');

        $client = $this->getClient();

        $input = new EncryptRequest([
            'KeyId' => 'change me',
            'Plaintext' => 'change me',
            'EncryptionContext' => ['change me' => 'change me'],
            'GrantTokens' => ['change me'],
            'EncryptionAlgorithm' => 'change me',
        ]);
        $result = $client->encrypt($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getCiphertextBlob());
        self::assertSame('changeIt', $result->getKeyId());
        self::assertSame('changeIt', $result->getEncryptionAlgorithm());
    }

    public function testGenerateDataKey(): void
    {
        self::markTestIncomplete('Cannot test GenerateData without the ability to create keys available.');

        $client = $this->getClient();

        $input = new GenerateDataKeyRequest([
            'KeyId' => 'change me',
            'EncryptionContext' => ['change me' => 'change me'],
            'NumberOfBytes' => 1337,
            'KeySpec' => 'change me',
            'GrantTokens' => ['change me'],
        ]);
        $result = $client->generateDataKey($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getCiphertextBlob());
        // self::assertTODO(expected, $result->getPlaintext());
        self::assertSame('changeIt', $result->getKeyId());
    }

    public function testListAliases(): void
    {
        self::markTestIncomplete('Cannot test Decrypt without the ability to create keys available.');

        $client = $this->getClient();

        $input = new ListAliasesRequest([
            'KeyId' => 'change me',
            'Limit' => 1337,
            'Marker' => 'change me',
        ]);
        $result = $client->listAliases($input);

        $result->resolve();

        // self::assertTODO(expected, $result->getAliases());
        self::assertSame('changeIt', $result->getNextMarker());
        self::assertFalse($result->getTruncated());
    }

    private function getClient(): KmsClient
    {
        self::fail('Not implemented');

        return new KmsClient([
            'endpoint' => 'http://localhost',
        ], new NullProvider());
    }
}
