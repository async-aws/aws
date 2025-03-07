<?php

namespace AsyncAws\Kms\Tests\Unit;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Result;
use AsyncAws\Core\Test\TestCase;
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
use AsyncAws\Kms\Result\CreateKeyResponse;
use AsyncAws\Kms\Result\DecryptResponse;
use AsyncAws\Kms\Result\EncryptResponse;
use AsyncAws\Kms\Result\GenerateDataKeyResponse;
use AsyncAws\Kms\Result\GetPublicKeyResponse;
use AsyncAws\Kms\Result\ListAliasesResponse;
use AsyncAws\Kms\Result\SignResponse;
use AsyncAws\Kms\Result\VerifyResponse;
use Symfony\Component\HttpClient\MockHttpClient;

class KmsClientTest extends TestCase
{
    public function testCreateAlias(): void
    {
        $client = new KmsClient([], new NullProvider(), new MockHttpClient());

        $input = new CreateAliasRequest([
            'AliasName' => 'change me',
            'TargetKeyId' => 'change me',
        ]);
        $result = $client->createAlias($input);

        self::assertInstanceOf(Result::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testCreateKey(): void
    {
        $client = new KmsClient([], new NullProvider(), new MockHttpClient());

        $input = new CreateKeyRequest([
        ]);
        $result = $client->createKey($input);

        self::assertInstanceOf(CreateKeyResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

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

    public function testGetPublicKey(): void
    {
        $client = new KmsClient([], new NullProvider(), new MockHttpClient());

        $input = new GetPublicKeyRequest([
            'KeyId' => 'change me',
        ]);
        $result = $client->getPublicKey($input);

        self::assertInstanceOf(GetPublicKeyResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testListAliases(): void
    {
        $client = new KmsClient([], new NullProvider(), new MockHttpClient());

        $input = new ListAliasesRequest([
        ]);
        $result = $client->listAliases($input);

        self::assertInstanceOf(ListAliasesResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testSign(): void
    {
        $client = new KmsClient([], new NullProvider(), new MockHttpClient());

        $input = new SignRequest([
            'KeyId' => 'signing_key',
            'Message' => '<message to be signed>',

            'SigningAlgorithm' => SigningAlgorithmSpec::RSASSA_PSS_SHA_512,
        ]);
        $result = $client->sign($input);

        self::assertInstanceOf(SignResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testVerify(): void
    {
        $client = new KmsClient([], new NullProvider(), new MockHttpClient());

        $input = new VerifyRequest([
            'KeyId' => 'signing_key',
            'Message' => '<message to be signed>',
            'Signature' => 'change me',
            'SigningAlgorithm' => SigningAlgorithmSpec::RSASSA_PSS_SHA_512,
        ]);
        $result = $client->verify($input);

        self::assertInstanceOf(VerifyResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }
}
