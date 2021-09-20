<?php

namespace AsyncAws\AppSync\Tests\Unit\Input;

use AsyncAws\AppSync\Input\UpdateFunctionRequest;
use AsyncAws\AppSync\ValueObject\LambdaConflictHandlerConfig;
use AsyncAws\AppSync\ValueObject\SyncConfig;
use AsyncAws\Core\Test\TestCase;

class UpdateFunctionRequestTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new UpdateFunctionRequest([
            'apiId' => 'change me',
            'name' => 'change me',
            'description' => 'change me',
            'functionId' => 'change me',
            'dataSourceName' => 'change me',
            'requestMappingTemplate' => 'change me',
            'responseMappingTemplate' => 'change me',
            'functionVersion' => 'change me',
            'syncConfig' => new SyncConfig([
                'conflictHandler' => 'change me',
                'conflictDetection' => 'change me',
                'lambdaConflictHandlerConfig' => new LambdaConflictHandlerConfig([
                    'lambdaConflictHandlerArn' => 'change me',
                ]),
            ]),
        ]);

        // see https://docs.aws.amazon.com/appsync/latest/APIReference/API_UpdateFunction.html
        $expected = '
            POST / HTTP/1.0
            Content-Type: application/json

            {
            "change": "it"
        }
                ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
