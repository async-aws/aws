<?php

namespace AsyncAws\Athena\ValueObject;

/**
 * Contains metadata for a column in a table.
 */
final class Column
{
    /**
     * The name of the column.
     */
    private $name;

    /**
     * The data type of the column.
     */
    private $type;

    /**
     * Optional information about the column.
     */
    private $comment;

    /**
     * @param array{
     *   Name: string,
     *   Type?: null|string,
     *   Comment?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->name = $input['Name'] ?? null;
        $this->type = $input['Type'] ?? null;
        $this->comment = $input['Comment'] ?? null;
    }

    /**
     * @param array{
     *   Name: string,
     *   Type?: null|string,
     *   Comment?: null|string,
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
}
