<?php

namespace AsyncAws\SecretsManager\Tests\Unit\Input;

use AsyncAws\Core\Test\TestCase;
use AsyncAws\SecretsManager\Input\GetSecretValueRequest;

class GetSecretValueRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new GetSecretValueRequest([
            'SecretId' => 'MyTestDatabaseSecret',
            'VersionId' => 'v2',
            'VersionStage' => 'AWSPREVIOUS',
        ]);

        // see example-1.json from SDK
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/x-amz-json-1.1
            x-amz-target: secretsmanager.GetSecretValue

            {
                "SecretId": "MyTestDatabaseSecret",
                "VersionId": "v2",
                "VersionStage": "AWSPREVIOUS"
            }
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
