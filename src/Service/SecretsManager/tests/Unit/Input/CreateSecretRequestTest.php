<?php

namespace AsyncAws\SecretsManager\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\SecretsManager\Input\CreateSecretRequest;
use AsyncAws\SecretsManager\ValueObject\ReplicaRegionType;
use AsyncAws\SecretsManager\ValueObject\Tag;

class CreateSecretRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new CreateSecretRequest([
            'Name' => 'MyTestDatabaseSecret',
            'ClientRequestToken' => 'EXAMPLE1-90ab-cdef-fedc-ba987SECRET1',
            'Description' => 'My test database secret created with the CLI',
            'KmsKeyId' => 'change me',
            'SecretString' => '{"username":"david","password":"BnQw!XDWgaEeT9XGTT29"}',
            'Tags' => [new Tag([
                'Key' => 'test-tag',
                'Value' => 'tag-value',
            ])],
            'AddReplicaRegions' => [new ReplicaRegionType([
                'Region' => 'eu-west-1',
                'KmsKeyId' => 'change me',
            ])],
            'ForceOverwriteReplicaSecret' => false,
        ]);

        // see example-1.json from SDK
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            x-amz-target: secretsmanager.CreateSecret

            {
                "ClientRequestToken": "EXAMPLE1-90ab-cdef-fedc-ba987SECRET1",
                "Description": "My test database secret created with the CLI",
                "Name": "MyTestDatabaseSecret",
                "SecretString": "{\\"username\\":\\"david\\",\\"password\\":\\"BnQw!XDWgaEeT9XGTT29\\"}",
                "Tags": [
                    {
                        "Key": "test-tag",
                        "Value": "tag-value"
                    }
                ],
                "AddReplicaRegions": [
                    {
                        "KmsKeyId": "change me",
                        "Region": "eu-west-1"
                    }
                ],
                "ForceOverwriteReplicaSecret": false,
                "KmsKeyId": "change me"
            }
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }

    public function testRequestWithoutToken(): void
    {
        $input = new CreateSecretRequest([
            'Name' => 'MyTestDatabaseSecret',
        ]);

        $content = json_decode($input->request()->getBody()->stringify(), true);

        self::assertSame(['Name', 'ClientRequestToken'], array_keys($content));
        self::assertSame('MyTestDatabaseSecret', $content['Name']);
        self::assertMatchesRegularExpression('{^[0-9a-f]{8}(?:-[0-9a-f]{4}){3}-[0-9a-f]{12}$}Di', $content['ClientRequestToken']);
    }
}
