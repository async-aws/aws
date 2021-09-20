<?php

namespace AsyncAws\AppSync\Tests\Unit\Input;

use AsyncAws\AppSync\Input\CreateResolverRequest;
use AsyncAws\AppSync\ValueObject\CachingConfig;
use AsyncAws\AppSync\ValueObject\LambdaConflictHandlerConfig;
use AsyncAws\AppSync\ValueObject\PipelineConfig;
use AsyncAws\AppSync\ValueObject\SyncConfig;
use AsyncAws\Core\Test\TestCase;

class CreateResolverRequestTest extends TestCase
{
    public function testRequest(): void
    {
        self::fail('Not implemented');

        $input = new CreateResolverRequest([
            'apiId' => 'change me',
            'typeName' => 'change me',
            'fieldName' => 'change me',
            'dataSourceName' => 'change me',
            'requestMappingTemplate' => 'change me',
            'responseMappingTemplate' => 'change me',
            'kind' => 'change me',
            'pipelineConfig' => new PipelineConfig([
                'functions' => ['change me'],
            ]),
            'syncConfig' => new SyncConfig([
                'conflictHandler' => 'change me',
                'conflictDetection' => 'change me',
                'lambdaConflictHandlerConfig' => new LambdaConflictHandlerConfig([
                    'lambdaConflictHandlerArn' => 'change me',
                ]),
            ]),
            'cachingConfig' => new CachingConfig([
                'ttl' => 1337,
                'cachingKeys' => ['change me'],
            ]),
        ]);

        // see https://docs.aws.amazon.com/appsync/latest/APIReference/API_CreateResolver.html
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
