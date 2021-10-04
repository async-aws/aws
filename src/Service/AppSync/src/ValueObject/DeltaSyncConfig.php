<?php

namespace AsyncAws\AppSync\ValueObject;

/**
 * The `DeltaSyncConfig` for a versioned datasource.
 */
final class DeltaSyncConfig
{
    /**
     * The number of minutes an Item is stored in the datasource.
     */
    private $baseTableTtl;

    /**
     * The Delta Sync table name.
     */
    private $deltaSyncTableName;

    /**
     * The number of minutes a Delta Sync log entry is stored in the Delta Sync table.
     */
    private $deltaSyncTableTtl;

    /**
     * @param array{
     *   baseTableTTL?: null|string,
     *   deltaSyncTableName?: null|string,
     *   deltaSyncTableTTL?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->baseTableTtl = $input['baseTableTTL'] ?? null;
        $this->deltaSyncTableName = $input['deltaSyncTableName'] ?? null;
        $this->deltaSyncTableTtl = $input['deltaSyncTableTTL'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getBaseTableTtl(): ?string
    {
        return $this->baseTableTtl;
    }

    public function getDeltaSyncTableName(): ?string
    {
        return $this->deltaSyncTableName;
    }

    public function getDeltaSyncTableTtl(): ?string
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
