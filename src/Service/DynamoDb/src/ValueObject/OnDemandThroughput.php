<?php

namespace AsyncAws\DynamoDb\ValueObject;

/**
 * Sets the maximum number of read and write units for the specified on-demand table. If you use this parameter, you
 * must specify `MaxReadRequestUnits`, `MaxWriteRequestUnits`, or both.
 */
final class OnDemandThroughput
{
    /**
     * Maximum number of read request units for the specified table.
     *
     * To specify a maximum `OnDemandThroughput` on your table, set the value of `MaxReadRequestUnits` as greater than or
     * equal to 1. To remove the maximum `OnDemandThroughput` that is currently set on your table, set the value of
     * `MaxReadRequestUnits` to -1.
     *
     * @var int|null
     */
    private $maxReadRequestUnits;

    /**
     * Maximum number of write request units for the specified table.
     *
     * To specify a maximum `OnDemandThroughput` on your table, set the value of `MaxWriteRequestUnits` as greater than or
     * equal to 1. To remove the maximum `OnDemandThroughput` that is currently set on your table, set the value of
     * `MaxWriteRequestUnits` to -1.
     *
     * @var int|null
     */
    private $maxWriteRequestUnits;

    /**
     * @param array{
     *   MaxReadRequestUnits?: int|null,
     *   MaxWriteRequestUnits?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->maxReadRequestUnits = $input['MaxReadRequestUnits'] ?? null;
        $this->maxWriteRequestUnits = $input['MaxWriteRequestUnits'] ?? null;
    }

    /**
     * @param array{
     *   MaxReadRequestUnits?: int|null,
     *   MaxWriteRequestUnits?: int|null,
     * }|OnDemandThroughput $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getMaxReadRequestUnits(): ?int
    {
        return $this->maxReadRequestUnits;
    }

    public function getMaxWriteRequestUnits(): ?int
    {
        return $this->maxWriteRequestUnits;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->maxReadRequestUnits) {
            $payload['MaxReadRequestUnits'] = $v;
        }
        if (null !== $v = $this->maxWriteRequestUnits) {
            $payload['MaxWriteRequestUnits'] = $v;
        }

        return $payload;
    }
}
