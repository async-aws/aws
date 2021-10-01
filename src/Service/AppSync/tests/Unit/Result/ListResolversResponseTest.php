<?php

namespace AsyncAws\AppSync\Tests\Unit\Result;

use AsyncAws\AppSync\Enum\ConflictDetectionType;
use AsyncAws\AppSync\Enum\ConflictHandlerType;
use AsyncAws\AppSync\Enum\ResolverKind;
use AsyncAws\AppSync\Result\ListResolversResponse;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class ListResolversResponseTest extends TestCase
{
    public function testListResolversResponse(): void
    {
        // see https://docs.aws.amazon.com/appsync/latest/APIReference/API_ListResolvers.html
        $response = new SimpleMockedResponse('{
           "nextToken": "token",
           "resolvers": [
              {
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
                    "conflictHandler": "AUTOMERGE",
                    "lambdaConflictHandlerConfig": {
                       "lambdaConflictHandlerArn": "aws::handler"
                    }
                 },
                 "typeName": "type"
              }
           ]
        }');

        $client = new MockHttpClient($response);
        $result = new ListResolversResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        self::assertSame('token', $result->getnextToken());

        self::assertCount(1, $result->getResolvers());
        $resolver = $result->getResolvers()[0];

        self::assertEquals('source', $resolver->getDataSourceName());
        self::assertEquals('field', $resolver->getFieldName());
        self::assertEquals(ResolverKind::UNIT, $resolver->getKind());
        self::assertEquals('requestMapping', $resolver->getRequestMappingTemplate());
        self::assertEquals('responseMapping', $resolver->getResponseMappingTemplate());
        self::assertEquals('aws::resolver', $resolver->getResolverArn());
        self::assertEquals('type', $resolver->getTypeName());

        self::assertNotNull($resolver->getCachingConfig());
        self::assertCount(1, $resolver->getCachingConfig()->getCachingKeys());
        self::assertContains('cacheKey', $resolver->getCachingConfig()->getCachingKeys());
        self::assertEquals(1337, $resolver->getCachingConfig()->getTtl());

        self::assertNotNull($resolver->getPipelineConfig());
        self::assertCount(1, $resolver->getPipelineConfig()->getFunctions());
        self::assertContains('pipelineFunction', $resolver->getPipelineConfig()->getFunctions());

        self::assertNotNull($resolver->getSyncConfig());
        self::assertEquals(ConflictDetectionType::NONE, $resolver->getSyncConfig()->getConflictDetection());
        self::assertEquals(ConflictHandlerType::AUTOMERGE, $resolver->getSyncConfig()->getConflictHandler());
        self::assertEquals('aws::handler', $resolver->getSyncConfig()->getLambdaConflictHandlerConfig()->getLambdaConflictHandlerArn());
    }
}
