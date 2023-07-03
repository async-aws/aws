<?php

namespace AsyncAws\TimestreamQuery\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Represents a single row in the query results.
 */
final class Row
{
    /**
     * List of data points in a single row of the result set.
     *
     * @var Datum[]
     */
    private $data;

    /**
     * @param array{
     *   Data: array<Datum|array>,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->data = isset($input['Data']) ? array_map([Datum::class, 'create'], $input['Data']) : $this->throwException(new InvalidArgument('Missing required field "Data".'));
    }

    /**
     * @param array{
     *   Data: array<Datum|array>,
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
        return $this->data;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
