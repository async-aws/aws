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
        $this->indexName = $input['IndexName'] ?? null;
        $this->provisionedThroughput = isset($input['ProvisionedThroughput']) ? ProvisionedThroughput::create($input['ProvisionedThroughput']) : null;
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
        if (null === $v = $this->indexName) {
            throw new InvalidArgument(sprintf('Missing parameter "IndexName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['IndexName'] = $v;
        if (null === $v = $this->provisionedThroughput) {
            throw new InvalidArgument(sprintf('Missing parameter "ProvisionedThroughput" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['ProvisionedThroughput'] = $v->requestBody();

        return $payload;
    }
}
