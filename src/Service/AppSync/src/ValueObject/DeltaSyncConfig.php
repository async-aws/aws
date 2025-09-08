<?php

namespace AsyncAws\AppSync\ValueObject;

/**
 * Describes a Delta Sync configuration.
 */
final class DeltaSyncConfig
{
    /**
     * The number of minutes that an Item is stored in the data source.
     *
     * @var int|null
     */
    private $baseTableTtl;

    /**
     * The Delta Sync table name.
     *
     * @var string|null
     */
    private $deltaSyncTableName;

    /**
     * The number of minutes that a Delta Sync log entry is stored in the Delta Sync table.
     *
     * @var int|null
     */
    private $deltaSyncTableTtl;

    /**
     * @param array{
     *   baseTableTTL?: int|null,
     *   deltaSyncTableName?: string|null,
     *   deltaSyncTableTTL?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->baseTableTtl = $input['baseTableTTL'] ?? null;
        $this->deltaSyncTableName = $input['deltaSyncTableName'] ?? null;
        $this->deltaSyncTableTtl = $input['deltaSyncTableTTL'] ?? null;
    }

    /**
     * @param array{
     *   baseTableTTL?: int|null,
     *   deltaSyncTableName?: string|null,
     *   deltaSyncTableTTL?: int|null,
     * }|DeltaSyncConfig $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getBaseTableTtl(): ?int
    {
        return $this->baseTableTtl;
    }

    public function getDeltaSyncTableName(): ?string
    {
        return $this->deltaSyncTableName;
    }

    public function getDeltaSyncTableTtl(): ?int
    {
        return $this->deltaSyncTableTtl;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->baseTableTtl) {
            $payload['baseTableTTL'] = $v;
        }
        if (null !== $v = $this->deltaSyncTableName) {
            $payload['deltaSyncTableName'] = $v;
        }
        if (null !== $v = $this->deltaSyncTableTtl) {
            $payload['deltaSyncTableTTL'] = $v;
        }

        return $payload;
    }
}
