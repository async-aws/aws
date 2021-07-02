<?php

namespace AsyncAws\SecretsManager\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\SecretsManager\Input\DeleteSecretRequest;

class DeleteSecretRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new DeleteSecretRequest([
            'SecretId' => 'MyTestDatabaseSecret1',
            'RecoveryWindowInDays' => 7,
            'ForceDeleteWithoutRecovery' => false,
        ]);

        // see example-1.json from SDK
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            x-amz-target: secretsmanager.DeleteSecret

            {
                "RecoveryWindowInDays": 7,
                "ForceDeleteWithoutRecovery": false,
                "SecretId": "MyTestDatabaseSecret1"
            }
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
