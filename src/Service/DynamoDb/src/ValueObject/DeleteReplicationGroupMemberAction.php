<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

final class DeleteReplicationGroupMemberAction
{
    /**
     * The Region where the replica exists.
     */
    private $RegionName;

    /**
     * @param array{
     *   RegionName: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->RegionName = $input['RegionName'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getRegionName(): string
    {
        return $this->RegionName;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->RegionName) {
            throw new InvalidArgument(sprintf('Missing parameter "RegionName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['RegionName'] = $v;

        return $payload;
    }
}
