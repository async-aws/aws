<?php

namespace AsyncAws\Athena\ValueObject;

/**
 * Contains metadata information for a database in a data catalog.
 */
final class Database
{
    /**
     * The name of the database.
     */
    private $name;

    /**
     * An optional description of the database.
     */
    private $description;

    /**
     * A set of custom key/value pairs.
     */
    private $parameters;

    /**
     * @param array{
     *   Name: string,
     *   Description?: null|string,
     *   Parameters?: null|array<string, string>,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->name = $input['Name'] ?? null;
        $this->description = $input['Description'] ?? null;
        $this->parameters = $input['Parameters'] ?? null;
    }

    /**
     * @param array{
     *   Name: string,
     *   Description?: null|string,
     *   Parameters?: null|array<string, string>,
     * }|Database $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array<string, string>
     */
    public function getParameters(): array
    {
        return $this->parameters ?? [];
    }
}
