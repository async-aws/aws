<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Represents a global secondary index to be deleted from an existing table.
 */
final class DeleteGlobalSecondaryIndexAction
{
    /**
     * The name of the global secondary index to be deleted.
     */
    private $indexName;

    /**
     * @param array{
     *   IndexName: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->indexName = $input['IndexName'] ?? $this->throwException(new InvalidArgument('Missing required field "IndexName".'));
    }

    /**
     * @param array{
     *   IndexName: string,
     * }|DeleteGlobalSecondaryIndexAction $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getIndexName(): string
    {
        return $this->indexName;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->indexName;
        $payload['IndexName'] = $v;

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
