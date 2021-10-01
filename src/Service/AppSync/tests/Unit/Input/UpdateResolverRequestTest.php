<?php

namespace AsyncAws\AppSync\Tests\Unit\Input;

use AsyncAws\AppSync\Enum\ConflictDetectionType;
use AsyncAws\AppSync\Enum\ConflictHandlerType;
use AsyncAws\AppSync\Enum\ResolverKind;
use AsyncAws\AppSync\Input\UpdateResolverRequest;
use AsyncAws\AppSync\ValueObject\CachingConfig;
use AsyncAws\AppSync\ValueObject\LambdaConflictHandlerConfig;
use AsyncAws\AppSync\ValueObject\PipelineConfig;
use AsyncAws\AppSync\ValueObject\SyncConfig;
use AsyncAws\Core\Test\TestCase;

class UpdateResolverRequestTest extends TestCase
{
    public function testRequest(): void
    {
        $input = new UpdateResolverRequest([
            'apiId' => 'api123',
            'typeName' => 'type',
            'fieldName' => 'field',
            'dataSourceName' => 'dataSource',
            'requestMappingTemplate' => 'requestMapping',
            'responseMappingTemplate' => 'responseMapping',
            'kind' => ResolverKind::UNIT,
            'pipelineConfig' => new PipelineConfig([
                'functions' => ['pipelineFunction'],
            ]),
            'syncConfig' => new SyncConfig([
                'conflictHandler' => ConflictHandlerType::AUTOMERGE,
                'conflictDetection' => ConflictDetectionType::NONE,
                'lambdaConflictHandlerConfig' => new LambdaConflictHandlerConfig([
                    'lambdaConflictHandlerArn' => 'aws::lambda',
                ]),
            ]),
            'cachingConfig' => new CachingConfig([
                'ttl' => 1337,
                'cachingKeys' => ['cacheKey'],
            ]),
        ]);

        // see https://docs.aws.amazon.com/appsync/latest/APIReference/API_UpdateResolver.html
        $expected = '
            POST /v1/apis/api123/types/type/resolvers/field HTTP/1.1
            Content-type: application/json

            {
               "cachingConfig": {
                  "cachingKeys": [ "cacheKey" ],
                  "ttl": 1337
               },
               "dataSourceName": "dataSource",
               "kind": "UNIT",
               "pipelineConfig": {
                  "functions": [ "pipelineFunction" ]
               },
               "requestMappingTemplate": "requestMapping",
               "responseMappingTemplate": "responseMapping",
               "syncConfig": {
                  "conflictDetection": "NONE",
                  "conflictHandler": "AUTOMERGE",
                  "lambdaConflictHandlerConfig": {
                     "lambdaConflictHandlerArn": "aws::lambda"
                  }
               }
            }
        ';

        self::assertRequestEqualsHttpRequest($expected, $input->request());
    }
}
