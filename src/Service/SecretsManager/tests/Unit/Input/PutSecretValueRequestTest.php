<?php

namespace AsyncAws\SecretsManager\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\SecretsManager\Input\PutSecretValueRequest;

class PutSecretValueRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new PutSecretValueRequest([
            'SecretId' => 'MyTestDatabaseSecret',
            'ClientRequestToken' => 'EXAMPLE2-90ab-cdef-fedc-ba987EXAMPLE',
            'SecretString' => '{"username":"david","password":"BnQw!XDWgaEeT9XGTT29"}',
            'VersionStages' => ['v2'],
        ]);

        // see example-1.json from SDK
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            x-amz-target: secretsmanager.PutSecretValue

            {
                "ClientRequestToken": "EXAMPLE2-90ab-cdef-fedc-ba987EXAMPLE",
                "SecretId": "MyTestDatabaseSecret",
                "SecretString": "{\\"username\\":\\"david\\",\\"password\\":\\"BnQw!XDWgaEeT9XGTT29\\"}",
                "VersionStages": ["v2"]
            }
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
