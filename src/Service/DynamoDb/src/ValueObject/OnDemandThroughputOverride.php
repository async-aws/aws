<?php

namespace AsyncAws\DynamoDb\ValueObject;

/**
 * Overrides the on-demand throughput settings for this replica table. If you don't specify a value for this parameter,
 * it uses the source table's on-demand throughput settings.
 */
final class OnDemandThroughputOverride
{
    /**
     * Maximum number of read request units for the specified replica table.
     *
     * @var int|null
     */
    private $maxReadRequestUnits;

    /**
     * @param array{
     *   MaxReadRequestUnits?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->maxReadRequestUnits = $input['MaxReadRequestUnits'] ?? null;
    }

    /**
     * @param array{
     *   MaxReadRequestUnits?: int|null,
     * }|OnDemandThroughputOverride $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getMaxReadRequestUnits(): ?int
    {
        return $this->maxReadRequestUnits;
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

        return $payload;
    }
}
