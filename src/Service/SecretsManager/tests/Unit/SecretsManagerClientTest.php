<?php

namespace AsyncAws\SecretsManager\Tests\Unit;

use AsyncAws\Core\Credentials\NullProvider;
use AsyncAws\Core\Test\TestCase;
use AsyncAws\SecretsManager\Input\CreateSecretRequest;
use AsyncAws\SecretsManager\Input\DeleteSecretRequest;
use AsyncAws\SecretsManager\Input\GetSecretValueRequest;
use AsyncAws\SecretsManager\Input\ListSecretsRequest;
use AsyncAws\SecretsManager\Input\PutSecretValueRequest;
use AsyncAws\SecretsManager\Input\UpdateSecretRequest;
use AsyncAws\SecretsManager\Result\CreateSecretResponse;
use AsyncAws\SecretsManager\Result\DeleteSecretResponse;
use AsyncAws\SecretsManager\Result\GetSecretValueResponse;
use AsyncAws\SecretsManager\Result\ListSecretsResponse;
use AsyncAws\SecretsManager\Result\PutSecretValueResponse;
use AsyncAws\SecretsManager\Result\UpdateSecretResponse;
use AsyncAws\SecretsManager\SecretsManagerClient;
use Symfony\Component\HttpClient\MockHttpClient;

class SecretsManagerClientTest extends TestCase
{
    public function testCreateSecret(): void
    {
        $client = new SecretsManagerClient([], new NullProvider(), new MockHttpClient());

        $input = new CreateSecretRequest([
            'Name' => 'change me',
        ]);
        $result = $client->CreateSecret($input);

        self::assertInstanceOf(CreateSecretResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testDeleteSecret(): void
    {
        $client = new SecretsManagerClient([], new NullProvider(), new MockHttpClient());

        $input = new DeleteSecretRequest([
            'SecretId' => 'change me',

        ]);
        $result = $client->DeleteSecret($input);

        self::assertInstanceOf(DeleteSecretResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testGetSecretValue(): void
    {
        $client = new SecretsManagerClient([], new NullProvider(), new MockHttpClient());

        $input = new GetSecretValueRequest([
            'SecretId' => 'change me',

        ]);
        $result = $client->GetSecretValue($input);

        self::assertInstanceOf(GetSecretValueResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testListSecrets(): void
    {
        $client = new SecretsManagerClient([], new NullProvider(), new MockHttpClient());

        $input = new ListSecretsRequest([

        ]);
        $result = $client->ListSecrets($input);

        self::assertInstanceOf(ListSecretsResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testPutSecretValue(): void
    {
        $client = new SecretsManagerClient([], new NullProvider(), new MockHttpClient());

        $input = new PutSecretValueRequest([
            'SecretId' => 'change me',

        ]);
        $result = $client->PutSecretValue($input);

        self::assertInstanceOf(PutSecretValueResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }

    public function testUpdateSecret(): void
    {
        $client = new SecretsManagerClient([], new NullProvider(), new MockHttpClient());

        $input = new UpdateSecretRequest([
            'SecretId' => 'change me',

        ]);
        $result = $client->UpdateSecret($input);

        self::assertInstanceOf(UpdateSecretResponse::class, $result);
        self::assertFalse($result->info()['resolved']);
    }
}
