<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Specifies the action to add a new witness Region to a MRSC global table. A MRSC global table can be configured with
 * either three replicas, or with two replicas and one witness.
 */
final class CreateGlobalTableWitnessGroupMemberAction
{
    /**
     * The Amazon Web Services Region name to be added as a witness Region for the MRSC global table. The witness must be in
     * a different Region than the replicas and within the same Region set:
     *
     * - US Region set: US East (N. Virginia), US East (Ohio), US West (Oregon)
     * - EU Region set: Europe (Ireland), Europe (London), Europe (Paris), Europe (Frankfurt)
     * - AP Region set: Asia Pacific (Tokyo), Asia Pacific (Seoul), Asia Pacific (Osaka)
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
     * }|CreateGlobalTableWitnessGroupMemberAction $input
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
