<?php

namespace AsyncAws\SecretsManager\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\SecretsManager\Input\UpdateSecretRequest;

class UpdateSecretRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new UpdateSecretRequest([
            'SecretId' => 'MyTestDatabaseSecret',
            'ClientRequestToken' => 'EXAMPLE1-90ab-cdef-fedc-ba987EXAMPLE',
            'Description' => 'This is a new description for the secret.',
        ]);

        // see example-1.json from SDK
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            x-amz-target: secretsmanager.UpdateSecret

            {
                "ClientRequestToken": "EXAMPLE1-90ab-cdef-fedc-ba987EXAMPLE",
                "Description": "This is a new description for the secret.",
                "SecretId": "MyTestDatabaseSecret"
            }
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
