<?php

namespace AsyncAws\AppSync\Tests\Unit\Result;

use AsyncAws\AppSync\Enum\ConflictDetectionType;
use AsyncAws\AppSync\Enum\ConflictHandlerType;
use AsyncAws\AppSync\Enum\ResolverKind;
use AsyncAws\AppSync\Result\UpdateResolverResponse;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class UpdateResolverResponseTest extends TestCase
{
    public function testUpdateResolverResponse(): void
    {
        // see https://docs.aws.amazon.com/appsync/latest/APIReference/API_UpdateResolver.html
        $response = new SimpleMockedResponse('{
           "resolver": {
              "cachingConfig": {
                 "cachingKeys": [ "cacheKey" ],
                 "ttl": 1337
              },
              "dataSourceName": "source",
              "fieldName": "field",
              "kind": "UNIT",
              "pipelineConfig": {
                 "functions": [ "pipelineFunction" ]
              },
              "requestMappingTemplate": "requestMapping",
              "resolverArn": "aws::resolver",
              "responseMappingTemplate": "responseMapping",
              "syncConfig": {
                 "conflictDetection": "NONE",
                 "conflictHandler": "LAMBDA",
                 "lambdaConflictHandlerConfig": {
                    "lambdaConflictHandlerArn": "aws::lambda"
                 }
              },
              "typeName": "type"
           }
        }');

        $client = new MockHttpClient($response);
        $result = new UpdateResolverResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        $resolver = $result->getResolver();
        self::assertNotNull($resolver);
        self::assertEquals('source', $resolver->getDataSourceName());
        self::assertEquals('field', $resolver->getFieldName());
        self::assertEquals(ResolverKind::UNIT, $resolver->getKind());
        self::assertEquals('requestMapping', $resolver->getRequestMappingTemplate());
        self::assertEquals('responseMapping', $resolver->getResponseMappingTemplate());
        self::assertEquals('type', $resolver->getTypeName());

        self::assertNotNull($resolver->getPipelineConfig());
        self::assertCount(1, $resolver->getPipelineConfig()->getFunctions());
        self::assertContains('pipelineFunction', $resolver->getPipelineConfig()->getFunctions());

        self::assertNotNull($resolver->getCachingConfig());
        self::assertEquals(1337, $resolver->getCachingConfig()->getTtl());
        self::assertCount(1, $resolver->getCachingConfig()->getCachingKeys());
        self::assertContains('cacheKey', $resolver->getCachingConfig()->getCachingKeys());

        self::assertNotNull($resolver->getSyncConfig());
        self::assertEquals(ConflictDetectionType::NONE, $resolver->getSyncConfig()->getConflictDetection());
        self::assertEquals(ConflictHandlerType::LAMBDA, $resolver->getSyncConfig()->getConflictHandler());
        self::assertNotNull($resolver->getSyncConfig()->getLambdaConflictHandlerConfig());
        self::assertEquals('aws::lambda', $resolver->getSyncConfig()->getLambdaConflictHandlerConfig()->getLambdaConflictHandlerArn());
    }
}
