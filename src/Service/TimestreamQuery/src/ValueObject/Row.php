<?php

namespace AsyncAws\TimestreamQuery\ValueObject;

/**
 * Represents a single row in the query results.
 */
final class Row
{
    /**
     * List of data points in a single row of the result set.
     */
    private $data;

    /**
     * @param array{
     *   Data: Datum[],
     * } $input
     */
    public function __construct(array $input)
    {
        $this->data = isset($input['Data']) ? array_map([Datum::class, 'create'], $input['Data']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return Datum[]
     */
    public function getData(): array
    {
        return $this->data ?? [];
    }
}
