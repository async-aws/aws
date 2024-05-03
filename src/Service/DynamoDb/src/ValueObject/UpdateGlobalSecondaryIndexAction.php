<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Represents the new provisioned throughput settings to be applied to a global secondary index.
 */
final class UpdateGlobalSecondaryIndexAction
{
    /**
     * The name of the global secondary index to be updated.
     *
     * @var string
     */
    private $indexName;

    /**
     * Represents the provisioned throughput settings for the specified global secondary index.
     *
     * For current minimum and maximum provisioned throughput values, see Service, Account, and Table Quotas [^1] in the
     * *Amazon DynamoDB Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/Limits.html
     *
     * @var ProvisionedThroughput|null
     */
    private $provisionedThroughput;

    /**
     * Updates the maximum number of read and write units for the specified global secondary index. If you use this
     * parameter, you must specify `MaxReadRequestUnits`, `MaxWriteRequestUnits`, or both.
     *
     * @var OnDemandThroughput|null
     */
    private $onDemandThroughput;

    /**
     * @param array{
     *   IndexName: string,
     *   ProvisionedThroughput?: null|ProvisionedThroughput|array,
     *   OnDemandThroughput?: null|OnDemandThroughput|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->indexName = $input['IndexName'] ?? $this->throwException(new InvalidArgument('Missing required field "IndexName".'));
        $this->provisionedThroughput = isset($input['ProvisionedThroughput']) ? ProvisionedThroughput::create($input['ProvisionedThroughput']) : null;
        $this->onDemandThroughput = isset($input['OnDemandThroughput']) ? OnDemandThroughput::create($input['OnDemandThroughput']) : null;
    }

    /**
     * @param array{
     *   IndexName: string,
     *   ProvisionedThroughput?: null|ProvisionedThroughput|array,
     *   OnDemandThroughput?: null|OnDemandThroughput|array,
     * }|UpdateGlobalSecondaryIndexAction $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getIndexName(): string
    {
        return $this->indexName;
    }

    public function getOnDemandThroughput(): ?OnDemandThroughput
    {
        return $this->onDemandThroughput;
    }

    public function getProvisionedThroughput(): ?ProvisionedThroughput
    {
        return $this->provisionedThroughput;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->indexName;
        $payload['IndexName'] = $v;
        if (null !== $v = $this->provisionedThroughput) {
            $payload['ProvisionedThroughput'] = $v->requestBody();
        }
        if (null !== $v = $this->onDemandThroughput) {
            $payload['OnDemandThroughput'] = $v->requestBody();
        }

        return $payload;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
