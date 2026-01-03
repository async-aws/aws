<?php

namespace AsyncAws\AppSync\Result;

use AsyncAws\AppSync\Enum\ConflictDetectionType;
use AsyncAws\AppSync\Enum\ConflictHandlerType;
use AsyncAws\AppSync\Enum\ResolverKind;
use AsyncAws\AppSync\Enum\ResolverLevelMetricsConfig;
use AsyncAws\AppSync\Enum\RuntimeName;
use AsyncAws\AppSync\ValueObject\AppSyncRuntime;
use AsyncAws\AppSync\ValueObject\CachingConfig;
use AsyncAws\AppSync\ValueObject\LambdaConflictHandlerConfig;
use AsyncAws\AppSync\ValueObject\PipelineConfig;
use AsyncAws\AppSync\ValueObject\Resolver;
use AsyncAws\AppSync\ValueObject\SyncConfig;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class CreateResolverResponse extends Result
{
    /**
     * The `Resolver` object.
     *
     * @var Resolver|null
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

        $this->resolver = empty($data['resolver']) ? null : $this->populateResultResolver($data['resolver']);
    }

    private function populateResultAppSyncRuntime(array $json): AppSyncRuntime
    {
        return new AppSyncRuntime([
            'name' => !RuntimeName::exists((string) $json['name']) ? RuntimeName::UNKNOWN_TO_SDK : (string) $json['name'],
            'runtimeVersion' => (string) $json['runtimeVersion'],
        ]);
    }

    private function populateResultCachingConfig(array $json): CachingConfig
    {
        return new CachingConfig([
            'ttl' => (int) $json['ttl'],
            'cachingKeys' => !isset($json['cachingKeys']) ? null : $this->populateResultCachingKeys($json['cachingKeys']),
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

    private function populateResultLambdaConflictHandlerConfig(array $json): LambdaConflictHandlerConfig
    {
        return new LambdaConflictHandlerConfig([
            'lambdaConflictHandlerArn' => isset($json['lambdaConflictHandlerArn']) ? (string) $json['lambdaConflictHandlerArn'] : null,
        ]);
    }

    private function populateResultPipelineConfig(array $json): PipelineConfig
    {
        return new PipelineConfig([
            'functions' => !isset($json['functions']) ? null : $this->populateResultFunctionsIds($json['functions']),
        ]);
    }

    private function populateResultResolver(array $json): Resolver
    {
        return new Resolver([
            'typeName' => isset($json['typeName']) ? (string) $json['typeName'] : null,
            'fieldName' => isset($json['fieldName']) ? (string) $json['fieldName'] : null,
            'dataSourceName' => isset($json['dataSourceName']) ? (string) $json['dataSourceName'] : null,
            'resolverArn' => isset($json['resolverArn']) ? (string) $json['resolverArn'] : null,
            'requestMappingTemplate' => isset($json['requestMappingTemplate']) ? (string) $json['requestMappingTemplate'] : null,
            'responseMappingTemplate' => isset($json['responseMappingTemplate']) ? (string) $json['responseMappingTemplate'] : null,
            'kind' => isset($json['kind']) ? (!ResolverKind::exists((string) $json['kind']) ? ResolverKind::UNKNOWN_TO_SDK : (string) $json['kind']) : null,
            'pipelineConfig' => empty($json['pipelineConfig']) ? null : $this->populateResultPipelineConfig($json['pipelineConfig']),
            'syncConfig' => empty($json['syncConfig']) ? null : $this->populateResultSyncConfig($json['syncConfig']),
            'cachingConfig' => empty($json['cachingConfig']) ? null : $this->populateResultCachingConfig($json['cachingConfig']),
            'maxBatchSize' => isset($json['maxBatchSize']) ? (int) $json['maxBatchSize'] : null,
            'runtime' => empty($json['runtime']) ? null : $this->populateResultAppSyncRuntime($json['runtime']),
            'code' => isset($json['code']) ? (string) $json['code'] : null,
            'metricsConfig' => isset($json['metricsConfig']) ? (!ResolverLevelMetricsConfig::exists((string) $json['metricsConfig']) ? ResolverLevelMetricsConfig::UNKNOWN_TO_SDK : (string) $json['metricsConfig']) : null,
        ]);
    }

    private function populateResultSyncConfig(array $json): SyncConfig
    {
        return new SyncConfig([
            'conflictHandler' => isset($json['conflictHandler']) ? (!ConflictHandlerType::exists((string) $json['conflictHandler']) ? ConflictHandlerType::UNKNOWN_TO_SDK : (string) $json['conflictHandler']) : null,
            'conflictDetection' => isset($json['conflictDetection']) ? (!ConflictDetectionType::exists((string) $json['conflictDetection']) ? ConflictDetectionType::UNKNOWN_TO_SDK : (string) $json['conflictDetection']) : null,
            'lambdaConflictHandlerConfig' => empty($json['lambdaConflictHandlerConfig']) ? null : $this->populateResultLambdaConflictHandlerConfig($json['lambdaConflictHandlerConfig']),
        ]);
    }
}
