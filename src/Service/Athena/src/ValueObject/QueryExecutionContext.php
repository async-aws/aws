<?php

namespace AsyncAws\Athena\ValueObject;

/**
 * The database and data catalog context in which the query execution occurs.
 */
final class QueryExecutionContext
{
    /**
     * The name of the database used in the query execution. The database must exist in the catalog.
     *
     * @var string|null
     */
    private $database;

    /**
     * The name of the data catalog used in the query execution.
     *
     * @var string|null
     */
    private $catalog;

    /**
     * @param array{
     *   Database?: string|null,
     *   Catalog?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->database = $input['Database'] ?? null;
        $this->catalog = $input['Catalog'] ?? null;
    }

    /**
     * @param array{
     *   Database?: string|null,
     *   Catalog?: string|null,
     * }|QueryExecutionContext $input
     */
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
