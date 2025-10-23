<?php

namespace AsyncAws\Athena\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Contains metadata information for a database in a data catalog.
 */
final class Database
{
    /**
     * The name of the database.
     *
     * @var string
     */
    private $name;

    /**
     * An optional description of the database.
     *
     * @var string|null
     */
    private $description;

    /**
     * A set of custom key/value pairs.
     *
     * @var array<string, string>|null
     */
    private $parameters;

    /**
     * @param array{
     *   Name: string,
     *   Description?: string|null,
     *   Parameters?: array<string, string>|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->name = $input['Name'] ?? $this->throwException(new InvalidArgument('Missing required field "Name".'));
        $this->description = $input['Description'] ?? null;
        $this->parameters = $input['Parameters'] ?? null;
    }

    /**
     * @param array{
     *   Name: string,
     *   Description?: string|null,
     *   Parameters?: array<string, string>|null,
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

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
