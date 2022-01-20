<?php

namespace AsyncAws\Kms\Tests\Integration;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kms\Enum\DataKeySpec;
use AsyncAws\Kms\Enum\KeySpec;
use AsyncAws\Kms\Enum\KeyUsageType;
use AsyncAws\Kms\Enum\OriginType;
use AsyncAws\Kms\Input\CreateAliasRequest;
use AsyncAws\Kms\Input\CreateKeyRequest;
use AsyncAws\Kms\Input\DecryptRequest;
use AsyncAws\Kms\Input\EncryptRequest;
use AsyncAws\Kms\Input\GenerateDataKeyRequest;
use AsyncAws\Kms\Input\ListAliasesRequest;
use AsyncAws\Kms\KmsClient;
use AsyncAws\Kms\ValueObject\Tag;

class KmsClientTest extends TestCase
{
    public function testCreateKey(): void
    {
        $client = $this->getClient();

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
        $result = $client->createKey($input);

        $result->resolve();

        self::assertTrue($result->getKeyMetadata()->getEnabled());
        self::assertSame('My key', $result->getKeyMetadata()->getDescription());
        self::assertNull($result->getKeyMetadata()->getDeletionDate());
    }

    public function testDecrypt(): void
    {
        $client = $this->getClient();
        $key = $client->createKey(['KeyUsage' => KeyUsageType::ENCRYPT_DECRYPT]);
        $encrypted = $client->encrypt([
            'KeyId' => $key->getKeyMetadata()->getKeyId(),
            'Plaintext' => 'hello world',
        ]);

        $input = new DecryptRequest([
            'KeyId' => $key->getKeyMetadata()->getKeyId(),
            'CiphertextBlob' => $encrypted->getCiphertextBlob(),
        ]);
        $result = $client->decrypt($input);

        $result->resolve();

        self::assertSame('hello world', $result->getPlaintext());
    }

    public function testEncrypt(): void
    {
        $client = $this->getClient();
        $key = $client->createKey(['KeyUsage' => KeyUsageType::ENCRYPT_DECRYPT]);

        $input = new EncryptRequest([
            'KeyId' => $key->getKeyMetadata()->getKeyId(),
            'Plaintext' => 'hello world',
        ]);
        $result = $client->encrypt($input);

        $result->resolve();

        self::assertStringStartsWith('Karn:aws:kms:', $result->getCiphertextBlob());
    }

    public function testGenerateDataKey(): void
    {
        $client = $this->getClient();
        $key = $client->createKey(['KeyUsage' => KeyUsageType::ENCRYPT_DECRYPT]);

        $input = new GenerateDataKeyRequest([
            'KeyId' => $key->getKeyMetadata()->getKeyId(),
            'KeySpec' => DataKeySpec::AES_256,
        ]);
        $result = $client->generateDataKey($input);

        $result->resolve();

        self::assertStringStartsWith('Karn:aws:kms:', $result->getCiphertextBlob());
    }

    public function testCreateAlias(): void
    {
        $client = $this->getClient();
        $key = $client->createKey(['KeyUsage' => KeyUsageType::ENCRYPT_DECRYPT]);

        $input = new CreateAliasRequest([
            'AliasName' => 'alias/demo-' . uniqid('', true),
            'TargetKeyId' => $key->getKeyMetadata()->getKeyId(),
        ]);
        $result = $client->createAlias($input);

        $result->resolve();

        $this->expectNotToPerformAssertions();
    }

    public function testListAliases(): void
    {
        $client = $this->getClient();
        $key = $client->createKey(['KeyUsage' => KeyUsageType::ENCRYPT_DECRYPT]);

        $input = new ListAliasesRequest([
            'KeyId' => $key->getKeyMetadata()->getKeyId(),
        ]);

        $result = $client->listAliases($input);
        self::assertCount(0, $result);

        $client->createAlias([
            'AliasName' => $name = 'alias/demo-' . uniqid('', true),
            'TargetKeyId' => $key->getKeyMetadata()->getKeyId(),
        ]);

        $result = $client->listAliases($input);
        self::assertCount(1, $result);
        self::assertSame($name, iterator_to_array($result)[0]->getAliasName());
    }

    private function getClient(): KmsClient
    {
        return new KmsClient([
            'endpoint' => 'http://localhost:4579',
        ], new NullProvider());
    }
}
