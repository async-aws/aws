<?php

namespace AsyncAws\Athena\ValueObject;

/**
 * The database in which the query execution occurred.
 */
final class QueryExecutionContext
{
    /**
     * The name of the database used in the query execution.
     */
    private $database;

    /**
     * The name of the data catalog used in the query execution.
     */
    private $catalog;

    /**
     * @param array{
     *   Database?: null|string,
     *   Catalog?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->database = $input['Database'] ?? null;
        $this->catalog = $input['Catalog'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCatalog(): ?string
    {
        return $this->catalog;
    }

    public function getDatabase(): ?string
    {
        return $this->database;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->database) {
            $payload['Database'] = $v;
        }
        if (null !== $v = $this->catalog) {
            $payload['Catalog'] = $v;
        }

        return $payload;
    }
}
