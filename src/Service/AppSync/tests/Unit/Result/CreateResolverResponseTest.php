<?php

namespace AsyncAws\AppSync\Tests\Unit\Result;

use AsyncAws\AppSync\Enum\ConflictDetectionType;
use AsyncAws\AppSync\Enum\ConflictHandlerType;
use AsyncAws\AppSync\Enum\ResolverKind;
use AsyncAws\AppSync\Result\CreateResolverResponse;
use AsyncAws\Core\Response;
use AsyncAws\Core\Test\Http\SimpleMockedResponse;
use AsyncAws\Core\Test\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;

class CreateResolverResponseTest extends TestCase
{
    public function testCreateResolverResponse(): void
    {
        // see https://docs.aws.amazon.com/appsync/latest/APIReference/API_CreateResolver.html
        $response = new SimpleMockedResponse('{
           "resolver": {
              "cachingConfig": {
                 "cachingKeys": [ "cacheKey" ],
                 "ttl": 123
              },
              "dataSourceName": "source",
              "fieldName": "field",
              "kind": "PIPELINE",
              "pipelineConfig": {
                 "functions": [ "someFunction" ]
              },
              "requestMappingTemplate": "requestTemplate",
              "resolverArn": "aws::resolver",
              "responseMappingTemplate": "responseTemplate",
              "syncConfig": {
                 "conflictDetection": "VERSION",
                 "conflictHandler": "OPTIMISTIC_CONCURRENCY",
                 "lambdaConflictHandlerConfig": {
                    "lambdaConflictHandlerArn": "aws::handler"
                 }
              },
              "typeName": "type"
           }
        }');

        $client = new MockHttpClient($response);
        $result = new CreateResolverResponse(new Response($client->request('POST', 'http://localhost'), $client, new NullLogger()));

        $resolver = $result->getResolver();
        self::assertNotNull($resolver);
        self::assertEquals($resolver->getTypeName(), 'type');
        self::assertEquals($resolver->getFieldName(), 'field');
        self::assertEquals($resolver->getDataSourceName(), 'source');
        self::assertEquals($resolver->getKind(), ResolverKind::PIPELINE);
        self::assertEquals($resolver->getRequestMappingTemplate(), 'requestTemplate');
        self::assertEquals($resolver->getResponseMappingTemplate(), 'responseTemplate');

        $syncConfig = $resolver->getSyncConfig();
        self::assertNotNull($syncConfig);
        self::assertEquals($syncConfig->getConflictDetection(), ConflictDetectionType::VERSION);
        self::assertEquals($syncConfig->getConflictHandler(), ConflictHandlerType::OPTIMISTIC_CONCURRENCY);
        self::assertEquals($syncConfig->getLambdaConflictHandlerConfig()->getLambdaConflictHandlerArn(), 'aws::handler');

        $cachingConfig = $resolver->getCachingConfig();
        self::assertNotNull($cachingConfig);
        self::assertEquals($cachingConfig->getTtl(), 123);
        self::assertCount(1, $cachingConfig->getCachingKeys());
        self::assertContains('cacheKey', $cachingConfig->getCachingKeys());

        $pipelineConfig = $resolver->getPipelineConfig();
        self::assertNotNull($pipelineConfig);
        self::assertCount(1, $pipelineConfig->getFunctions());
        self::assertContains('someFunction', $pipelineConfig->getFunctions());
    }
}
