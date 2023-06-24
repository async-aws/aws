<?php

namespace AsyncAws\TimestreamWrite\ValueObject;

/**
 * Information on the records ingested by this request.
 */
final class RecordsIngested
{
    /**
     * Total count of successfully ingested records.
     */
    private $total;

    /**
     * Count of records ingested into the memory store.
     */
    private $memoryStore;

    /**
     * Count of records ingested into the magnetic store.
     */
    private $magneticStore;

    /**
     * @param array{
     *   Total?: null|int,
     *   MemoryStore?: null|int,
     *   MagneticStore?: null|int,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->total = $input['Total'] ?? null;
        $this->memoryStore = $input['MemoryStore'] ?? null;
        $this->magneticStore = $input['MagneticStore'] ?? null;
    }

    /**
     * @param array{
     *   Total?: null|int,
     *   MemoryStore?: null|int,
     *   MagneticStore?: null|int,
     * }|RecordsIngested $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getMagneticStore(): ?int
    {
        return $this->magneticStore;
    }

    public function getMemoryStore(): ?int
    {
        return $this->memoryStore;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }
}
