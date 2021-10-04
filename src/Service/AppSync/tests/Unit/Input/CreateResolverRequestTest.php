<?php

namespace AsyncAws\AppSync\Tests\Unit\Input;

use AsyncAws\AppSync\Enum\ConflictDetectionType;
use AsyncAws\AppSync\Enum\ConflictHandlerType;
use AsyncAws\AppSync\Enum\ResolverKind;
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
        $input = new CreateResolverRequest([
            'apiId' => 'apiId',
            'typeName' => 'foo',
            'fieldName' => 'bar',
            'dataSourceName' => 'source',
            'requestMappingTemplate' => 'requestTemplate',
            'responseMappingTemplate' => 'responseTemplate',
            'kind' => ResolverKind::PIPELINE,
            'pipelineConfig' => new PipelineConfig([
                'functions' => ['someFunction'],
            ]),
            'syncConfig' => new SyncConfig([
                'conflictHandler' => ConflictHandlerType::OPTIMISTIC_CONCURRENCY,
                'conflictDetection' => ConflictDetectionType::VERSION,
                'lambdaConflictHandlerConfig' => new LambdaConflictHandlerConfig([
                    'lambdaConflictHandlerArn' => 'aws::foo',
                ]),
            ]),
            'cachingConfig' => new CachingConfig([
                'ttl' => 1337,
                'cachingKeys' => ['cacheKey'],
            ]),
        ]);

        $expected = '
            POST /v1/apis/apiId/types/foo/resolvers HTTP/1.1 200
            Content-type: application/json

            {
                "cachingConfig": {
                    "cachingKeys": [ "cacheKey" ],
                    "ttl": 1337
                },
                "dataSourceName": "source",
                "fieldName": "bar",
                "kind": "PIPELINE",
                "pipelineConfig": {
                     "functions": [ "someFunction" ]
                },
                "requestMappingTemplate": "requestTemplate",
                "responseMappingTemplate": "responseTemplate",
                "syncConfig": {
                     "conflictDetection": "VERSION",
                     "conflictHandler": "OPTIMISTIC_CONCURRENCY",
                     "lambdaConflictHandlerConfig": {
                        "lambdaConflictHandlerArn": "aws::foo"
                     }
                }
            }
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
