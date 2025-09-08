<?php

namespace AsyncAws\TimestreamQuery\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Contains the metadata for query results such as the column names, data types, and other attributes.
 */
final class ColumnInfo
{
    /**
     * The name of the result set column. The name of the result set is available for columns of all data types except for
     * arrays.
     *
     * @var string|null
     */
    private $name;

    /**
     * The data type of the result set column. The data type can be a scalar or complex. Scalar data types are integers,
     * strings, doubles, Booleans, and others. Complex data types are types such as arrays, rows, and others.
     *
     * @var Type
     */
    private $type;

    /**
     * @param array{
     *   Name?: string|null,
     *   Type: Type|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->name = $input['Name'] ?? null;
        $this->type = isset($input['Type']) ? Type::create($input['Type']) : $this->throwException(new InvalidArgument('Missing required field "Type".'));
    }

    /**
     * @param array{
     *   Name?: string|null,
     *   Type: Type|array,
     * }|ColumnInfo $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getType(): Type
    {
        return $this->type;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
