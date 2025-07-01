<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Specifies the action to remove a witness Region from a MRSC global table. You cannot delete a single witness from a
 * MRSC global table - you must delete both a replica and the witness together. The deletion of both a witness and
 * replica converts the remaining replica to a single-Region DynamoDB table.
 */
final class DeleteGlobalTableWitnessGroupMemberAction
{
    /**
     * The witness Region name to be removed from the MRSC global table.
     *
     * @var string
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
     * }|DeleteGlobalTableWitnessGroupMemberAction $input
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
        $v = $this->regionName;
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
