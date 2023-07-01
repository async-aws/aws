<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Represents a replica to be deleted.
 */
final class DeleteReplicationGroupMemberAction
{
    /**
     * The Region where the replica exists.
     */
    private $regionName;

    /**
     * @param array{
     *   RegionName: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->regionName = $input['RegionName'] ?? $this->throwException(new InvalidArgument('Missing required field "RegionName".'));
    }

    /**
     * @param array{
     *   RegionName: string,
     * }|DeleteReplicationGroupMemberAction $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getRegionName(): string
    {
        return $this->regionName;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->regionName) {
            throw new InvalidArgument(sprintf('Missing parameter "RegionName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['RegionName'] = $v;

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
