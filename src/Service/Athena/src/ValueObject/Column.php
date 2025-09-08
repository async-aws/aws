<?php

namespace AsyncAws\Athena\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Contains metadata for a column in a table.
 */
final class Column
{
    /**
     * The name of the column.
     *
     * @var string
     */
    private $name;

    /**
     * The data type of the column.
     *
     * @var string|null
     */
    private $type;

    /**
     * Optional information about the column.
     *
     * @var string|null
     */
    private $comment;

    /**
     * @param array{
     *   Name: string,
     *   Type?: string|null,
     *   Comment?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->name = $input['Name'] ?? $this->throwException(new InvalidArgument('Missing required field "Name".'));
        $this->type = $input['Type'] ?? null;
        $this->comment = $input['Comment'] ?? null;
    }

    /**
     * @param array{
     *   Name: string,
     *   Type?: string|null,
     *   Comment?: string|null,
     * }|Column $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): ?string
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
