<?php

namespace AsyncAws\AppSync\Result;

use AsyncAws\AppSync\ValueObject\CachingConfig;
use AsyncAws\AppSync\ValueObject\LambdaConflictHandlerConfig;
use AsyncAws\AppSync\ValueObject\PipelineConfig;
use AsyncAws\AppSync\ValueObject\Resolver;
use AsyncAws\AppSync\ValueObject\SyncConfig;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class UpdateResolverResponse extends Result
{
    /**
     * The updated `Resolver` object.
     */
    private $resolver;

    public function getResolver(): ?Resolver
    {
        $this->initialize();

        return $this->resolver;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->resolver = empty($data['resolver']) ? null : new Resolver([
            'typeName' => isset($data['resolver']['typeName']) ? (string) $data['resolver']['typeName'] : null,
            'fieldName' => isset($data['resolver']['fieldName']) ? (string) $data['resolver']['fieldName'] : null,
            'dataSourceName' => isset($data['resolver']['dataSourceName']) ? (string) $data['resolver']['dataSourceName'] : null,
            'resolverArn' => isset($data['resolver']['resolverArn']) ? (string) $data['resolver']['resolverArn'] : null,
            'requestMappingTemplate' => isset($data['resolver']['requestMappingTemplate']) ? (string) $data['resolver']['requestMappingTemplate'] : null,
            'responseMappingTemplate' => isset($data['resolver']['responseMappingTemplate']) ? (string) $data['resolver']['responseMappingTemplate'] : null,
            'kind' => isset($data['resolver']['kind']) ? (string) $data['resolver']['kind'] : null,
            'pipelineConfig' => empty($data['resolver']['pipelineConfig']) ? null : new PipelineConfig([
                'functions' => !isset($data['resolver']['pipelineConfig']['functions']) ? null : $this->populateResultFunctionsIds($data['resolver']['pipelineConfig']['functions']),
            ]),
            'syncConfig' => empty($data['resolver']['syncConfig']) ? null : new SyncConfig([
                'conflictHandler' => isset($data['resolver']['syncConfig']['conflictHandler']) ? (string) $data['resolver']['syncConfig']['conflictHandler'] : null,
                'conflictDetection' => isset($data['resolver']['syncConfig']['conflictDetection']) ? (string) $data['resolver']['syncConfig']['conflictDetection'] : null,
                'lambdaConflictHandlerConfig' => empty($data['resolver']['syncConfig']['lambdaConflictHandlerConfig']) ? null : new LambdaConflictHandlerConfig([
                    'lambdaConflictHandlerArn' => isset($data['resolver']['syncConfig']['lambdaConflictHandlerConfig']['lambdaConflictHandlerArn']) ? (string) $data['resolver']['syncConfig']['lambdaConflictHandlerConfig']['lambdaConflictHandlerArn'] : null,
                ]),
            ]),
            'cachingConfig' => empty($data['resolver']['cachingConfig']) ? null : new CachingConfig([
                'ttl' => isset($data['resolver']['cachingConfig']['ttl']) ? (string) $data['resolver']['cachingConfig']['ttl'] : null,
                'cachingKeys' => !isset($data['resolver']['cachingConfig']['cachingKeys']) ? null : $this->populateResultCachingKeys($data['resolver']['cachingConfig']['cachingKeys']),
            ]),
        ]);
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
}
