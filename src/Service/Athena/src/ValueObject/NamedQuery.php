<?php

namespace AsyncAws\Athena\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * A query, where `QueryString` contains the SQL statements that make up the query.
 */
final class NamedQuery
{
    /**
     * The query name.
     *
     * @var string
     */
    private $name;

    /**
     * The query description.
     *
     * @var string|null
     */
    private $description;

    /**
     * The database to which the query belongs.
     *
     * @var string
     */
    private $database;

    /**
     * The SQL statements that make up the query.
     *
     * @var string
     */
    private $queryString;

    /**
     * The unique identifier of the query.
     *
     * @var string|null
     */
    private $namedQueryId;

    /**
     * The name of the workgroup that contains the named query.
     *
     * @var string|null
     */
    private $workGroup;

    /**
     * @param array{
     *   Name: string,
     *   Description?: string|null,
     *   Database: string,
     *   QueryString: string,
     *   NamedQueryId?: string|null,
     *   WorkGroup?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->name = $input['Name'] ?? $this->throwException(new InvalidArgument('Missing required field "Name".'));
        $this->description = $input['Description'] ?? null;
        $this->database = $input['Database'] ?? $this->throwException(new InvalidArgument('Missing required field "Database".'));
        $this->queryString = $input['QueryString'] ?? $this->throwException(new InvalidArgument('Missing required field "QueryString".'));
        $this->namedQueryId = $input['NamedQueryId'] ?? null;
        $this->workGroup = $input['WorkGroup'] ?? null;
    }

    /**
     * @param array{
     *   Name: string,
     *   Description?: string|null,
     *   Database: string,
     *   QueryString: string,
     *   NamedQueryId?: string|null,
     *   WorkGroup?: string|null,
     * }|NamedQuery $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDatabase(): string
    {
        return $this->database;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getNamedQueryId(): ?string
    {
        return $this->namedQueryId;
    }

    public function getQueryString(): string
    {
        return $this->queryString;
    }

    public function getWorkGroup(): ?string
    {
        return $this->workGroup;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
