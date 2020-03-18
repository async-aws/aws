<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

class DeleteGlobalSecondaryIndexAction
{
    /**
     * The name of the global secondary index to be deleted.
     */
    private $IndexName;

    /**
     * @param array{
     *   IndexName: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->IndexName = $input['IndexName'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getIndexName(): string
    {
        return $this->IndexName;
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

        return $payload;
    }
}
