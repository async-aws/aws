<?php

namespace AsyncAws\AppSync\Result;

use AsyncAws\AppSync\ValueObject\CachingConfig;
use AsyncAws\AppSync\ValueObject\LambdaConflictHandlerConfig;
use AsyncAws\AppSync\ValueObject\PipelineConfig;
use AsyncAws\AppSync\ValueObject\Resolver;
use AsyncAws\AppSync\ValueObject\SyncConfig;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class ListResolversResponse extends Result
{
    /**
     * The `Resolver` objects.
     */
    private $resolvers;

    /**
     * An identifier to be passed in the next request to this operation to return the next set of items in the list.
     */
    private $nextToken;

    public function getNextToken(): ?string
    {
        $this->initialize();

        return $this->nextToken;
    }

    /**
     * @return Resolver[]
     */
    public function getResolvers(): array
    {
        $this->initialize();

        return $this->resolvers;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->resolvers = empty($data['resolvers']) ? [] : $this->populateResultResolvers($data['resolvers']);
        $this->nextToken = isset($data['nextToken']) ? (string) $data['nextToken'] : null;
    }

    /**
     * @return string[]
     */
    private function populateResultCachingKeys(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? (string) $item : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }

    /**
     * @return string[]
     */
    private function populateResultFunctionsIds(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $a = isset($item) ? (string) $item : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }

    /**
     * @return Resolver[]
     */
    private function populateResultResolvers(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = new Resolver([
                'typeName' => isset($item['typeName']) ? (string) $item['typeName'] : null,
                'fieldName' => isset($item['fieldName']) ? (string) $item['fieldName'] : null,
                'dataSourceName' => isset($item['dataSourceName']) ? (string) $item['dataSourceName'] : null,
                'resolverArn' => isset($item['resolverArn']) ? (string) $item['resolverArn'] : null,
                'requestMappingTemplate' => isset($item['requestMappingTemplate']) ? (string) $item['requestMappingTemplate'] : null,
                'responseMappingTemplate' => isset($item['responseMappingTemplate']) ? (string) $item['responseMappingTemplate'] : null,
                'kind' => isset($item['kind']) ? (string) $item['kind'] : null,
                'pipelineConfig' => empty($item['pipelineConfig']) ? null : new PipelineConfig([
                    'functions' => !isset($item['pipelineConfig']['functions']) ? null : $this->populateResultFunctionsIds($item['pipelineConfig']['functions']),
                ]),
                'syncConfig' => empty($item['syncConfig']) ? null : new SyncConfig([
                    'conflictHandler' => isset($item['syncConfig']['conflictHandler']) ? (string) $item['syncConfig']['conflictHandler'] : null,
                    'conflictDetection' => isset($item['syncConfig']['conflictDetection']) ? (string) $item['syncConfig']['conflictDetection'] : null,
                    'lambdaConflictHandlerConfig' => empty($item['syncConfig']['lambdaConflictHandlerConfig']) ? null : new LambdaConflictHandlerConfig([
                        'lambdaConflictHandlerArn' => isset($item['syncConfig']['lambdaConflictHandlerConfig']['lambdaConflictHandlerArn']) ? (string) $item['syncConfig']['lambdaConflictHandlerConfig']['lambdaConflictHandlerArn'] : null,
                    ]),
                ]),
                'cachingConfig' => empty($item['cachingConfig']) ? null : new CachingConfig([
                    'ttl' => isset($item['cachingConfig']['ttl']) ? (string) $item['cachingConfig']['ttl'] : null,
                    'cachingKeys' => !isset($item['cachingConfig']['cachingKeys']) ? null : $this->populateResultCachingKeys($item['cachingConfig']['cachingKeys']),
                ]),
            ]);
        }

        return $items;
    }
}
