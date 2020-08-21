<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

final class UpdateGlobalSecondaryIndexAction
{
    /**
     * The name of the global secondary index to be updated.
     */
    private $IndexName;

    /**
     * Represents the provisioned throughput settings for the specified global secondary index.
     */
    private $ProvisionedThroughput;

    /**
     * @param array{
     *   IndexName: string,
     *   ProvisionedThroughput: ProvisionedThroughput|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->IndexName = $input['IndexName'] ?? null;
        $this->ProvisionedThroughput = isset($input['ProvisionedThroughput']) ? ProvisionedThroughput::create($input['ProvisionedThroughput']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getIndexName(): string
    {
        return $this->IndexName;
    }

    public function getProvisionedThroughput(): ProvisionedThroughput
    {
        return $this->ProvisionedThroughput;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->IndexName) {
            throw new InvalidArgument(sprintf('Missing parameter "IndexName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['IndexName'] = $v;
        if (null === $v = $this->ProvisionedThroughput) {
            throw new InvalidArgument(sprintf('Missing parameter "ProvisionedThroughput" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['ProvisionedThroughput'] = $v->requestBody();

        return $payload;
    }
}
