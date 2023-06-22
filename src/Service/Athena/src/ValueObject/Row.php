<?php

namespace AsyncAws\Athena\ValueObject;

/**
 * The rows that make up a query result table.
 */
final class Row
{
    /**
     * The data that populates a row in a query result table.
     */
    private $data;

    /**
     * @param array{
     *   Data?: null|Datum[],
     * } $input
     */
    public function __construct(array $input)
    {
        $this->data = isset($input['Data']) ? array_map([Datum::class, 'create'], $input['Data']) : null;
    }

    /**
     * @param array{
     *   Data?: null|Datum[],
     * }|Row $input
     */
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
