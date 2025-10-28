<?php

namespace AsyncAws\Kms\Tests\Integration;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\Kms\Enum\DataKeySpec;
use AsyncAws\Kms\Enum\KeySpec;
use AsyncAws\Kms\Enum\KeyUsageType;
use AsyncAws\Kms\Enum\MessageType;
use AsyncAws\Kms\Enum\OriginType;
use AsyncAws\Kms\Enum\SigningAlgorithmSpec;
use AsyncAws\Kms\Input\CreateAliasRequest;
use AsyncAws\Kms\Input\CreateKeyRequest;
use AsyncAws\Kms\Input\DecryptRequest;
use AsyncAws\Kms\Input\EncryptRequest;
use AsyncAws\Kms\Input\GenerateDataKeyRequest;
use AsyncAws\Kms\Input\GetPublicKeyRequest;
use AsyncAws\Kms\Input\ListAliasesRequest;
use AsyncAws\Kms\Input\SignRequest;
use AsyncAws\Kms\Input\VerifyRequest;
use AsyncAws\Kms\KmsClient;
use AsyncAws\Kms\ValueObject\Tag;

class KmsClientTest extends TestCase
{
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

    public function testGetPublicKey(): void
    {
        $client = $this->getClient();
        $key = $client->createKey([
            'KeyUsage' => KeyUsageType::ENCRYPT_DECRYPT,
            'KeySpec' => KeySpec::RSA_4096,
        ]);

        $input = new GetPublicKeyRequest([
            'KeyId' => $key->getKeyMetadata()->getArn(),
        ]);
        $result = $client->getPublicKey($input);

        $result->resolve();

        self::assertSame(550, \strlen($result->getPublicKey()));
        self::assertSame($key->getKeyMetadata()->getArn(), $result->getKeyId());
        self::assertSame(KeySpec::RSA_4096, $result->getKeySpec());
        self::assertSame(KeyUsageType::ENCRYPT_DECRYPT, $result->getKeyUsage());
    }

    public function testListAliases(): void
    {
        $client = $this->getClient();
        $key = $client->createKey(['KeyUsage' => KeyUsageType::ENCRYPT_DECRYPT]);

        $input = new ListAliasesRequest([
            'KeyId' => $key->getKeyMetadata()->getKeyId(),
        ]);

        $result = $client->listAliases($input);
        $result = iterator_to_array($result);
        self::assertCount(0, $result);

        $client->createAlias([
            'AliasName' => $name = 'alias/demo-' . uniqid('', true),
            'TargetKeyId' => $key->getKeyMetadata()->getKeyId(),
        ]);

        $result = $client->listAliases($input);
        $result = iterator_to_array($result);
        self::assertCount(1, $result);
        self::assertSame($name, $result[0]->getAliasName());
    }

    public function testSign(): void
    {
        $client = $this->getClient();

        $key = $client->createKey([
            'KeyUsage' => KeyUsageType::SIGN_VERIFY,
            'KeySpec' => KeySpec::RSA_4096,
        ]);

        $input = new SignRequest([
            'KeyId' => $key->getKeyMetadata()->getKeyId(),
            'Message' => '<message to be signed>',
            'MessageType' => MessageType::RAW,
            'SigningAlgorithm' => SigningAlgorithmSpec::RSASSA_PSS_SHA_512,
        ]);
        $result = $client->sign($input);

        $result->resolve();

        self::assertSame($key->getKeyMetadata()->getArn(), $result->getKeyId());
        self::assertSame('RSASSA_PSS_SHA_512', $result->getSigningAlgorithm());
    }

    public function testVerify(): void
    {
        $client = $this->getClient();
        $key = $client->createKey([
            'KeyUsage' => KeyUsageType::SIGN_VERIFY,
            'KeySpec' => KeySpec::RSA_4096,
        ]);

        $input = new SignRequest([
            'KeyId' => $key->getKeyMetadata()->getKeyId(),
            'Message' => '<message to be signed>',
            'MessageType' => MessageType::RAW,
            'SigningAlgorithm' => SigningAlgorithmSpec::RSASSA_PSS_SHA_512,
        ]);
        $result = $client->sign($input);

        $input = new VerifyRequest([
            'KeyId' => $key->getKeyMetadata()->getArn(),
            'Message' => '<message to be signed>',
            'MessageType' => MessageType::RAW,
            'Signature' => $result->getSignature(),
            'SigningAlgorithm' => SigningAlgorithmSpec::RSASSA_PSS_SHA_512,
            'DryRun' => false,
        ]);
        $result = $client->verify($input);

        $result->resolve();

        self::assertSame($key->getKeyMetadata()->getArn(), $result->getKeyId());
        self::asserttrue($result->getSignatureValid());
        self::assertSame(SigningAlgorithmSpec::RSASSA_PSS_SHA_512, $result->getSigningAlgorithm());
    }

    private function getClient(): KmsClient
    {
        return new KmsClient([
            'endpoint' => 'http://localhost:4579',
        ], new NullProvider());
    }
}
