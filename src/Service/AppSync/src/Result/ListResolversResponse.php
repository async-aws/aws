<?php

namespace AsyncAws\AppSync\Result;

use AsyncAws\AppSync\AppSyncClient;
use AsyncAws\AppSync\Enum\ConflictDetectionType;
use AsyncAws\AppSync\Enum\ConflictHandlerType;
use AsyncAws\AppSync\Enum\ResolverKind;
use AsyncAws\AppSync\Enum\ResolverLevelMetricsConfig;
use AsyncAws\AppSync\Enum\RuntimeName;
use AsyncAws\AppSync\Input\ListResolversRequest;
use AsyncAws\AppSync\ValueObject\AppSyncRuntime;
use AsyncAws\AppSync\ValueObject\CachingConfig;
use AsyncAws\AppSync\ValueObject\LambdaConflictHandlerConfig;
use AsyncAws\AppSync\ValueObject\PipelineConfig;
use AsyncAws\AppSync\ValueObject\Resolver;
use AsyncAws\AppSync\ValueObject\SyncConfig;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

/**
 * @implements \IteratorAggregate<Resolver>
 */
class ListResolversResponse extends Result implements \IteratorAggregate
{
    /**
     * The `Resolver` objects.
     *
     * @var Resolver[]
     */
    private $resolvers;

    /**
     * An identifier to pass in the next request to this operation to return the next set of items in the list.
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * Iterates over resolvers.
     *
     * @return \Traversable<Resolver>
     */
    public function getIterator(): \Traversable
    {
        yield from $this->getResolvers();
    }

    public function getNextToken(): ?string
    {
        $this->initialize();

        return $this->nextToken;
    }

    /**
     * @param bool $currentPageOnly When true, iterates over items of the current page. Otherwise also fetch items in the next pages.
     *
     * @return iterable<Resolver>
     */
    public function getResolvers(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->resolvers;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof AppSyncClient) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListResolversRequest) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if (null !== $page->nextToken) {
                $input->setNextToken($page->nextToken);

                $this->registerPrefetch($nextPage = $client->listResolvers($input));
            } else {
                $nextPage = null;
            }

            yield from $page->resolvers;

            if (null === $nextPage) {
                break;
            }

            $this->unregisterPrefetch($nextPage);
            $page = $nextPage;
        }
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->resolvers = empty($data['resolvers']) ? [] : $this->populateResultResolvers($data['resolvers']);
        $this->nextToken = isset($data['nextToken']) ? (string) $data['nextToken'] : null;
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

    /**
     * @return Resolver[]
     */
    private function populateResultResolvers(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultResolver($item);
        }

        return $items;
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
