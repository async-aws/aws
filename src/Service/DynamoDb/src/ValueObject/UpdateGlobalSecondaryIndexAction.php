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
     */
    private $indexName;

    /**
     * Represents the provisioned throughput settings for the specified global secondary index.
     *
     * For current minimum and maximum provisioned throughput values, see Service, Account, and Table Quotas [^1] in the
     * *Amazon DynamoDB Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/Limits.html
     */
    private $provisionedThroughput;

    /**
     * @param array{
     *   IndexName: string,
     *   ProvisionedThroughput: ProvisionedThroughput|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->indexName = $input['IndexName'] ?? $this->throwException(new InvalidArgument('Missing required field "IndexName".'));
        $this->provisionedThroughput = isset($input['ProvisionedThroughput']) ? ProvisionedThroughput::create($input['ProvisionedThroughput']) : $this->throwException(new InvalidArgument('Missing required field "ProvisionedThroughput".'));
    }

    /**
     * @param array{
     *   IndexName: string,
     *   ProvisionedThroughput: ProvisionedThroughput|array,
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

    public function getProvisionedThroughput(): ProvisionedThroughput
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
        $v = $this->provisionedThroughput;
        $payload['ProvisionedThroughput'] = $v->requestBody();

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
