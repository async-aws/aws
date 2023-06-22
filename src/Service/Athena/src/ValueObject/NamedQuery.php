<?php

namespace AsyncAws\Athena\ValueObject;

/**
 * A query, where `QueryString` contains the SQL statements that make up the query.
 */
final class NamedQuery
{
    /**
     * The query name.
     */
    private $name;

    /**
     * The query description.
     */
    private $description;

    /**
     * The database to which the query belongs.
     */
    private $database;

    /**
     * The SQL statements that make up the query.
     */
    private $queryString;

    /**
     * The unique identifier of the query.
     */
    private $namedQueryId;

    /**
     * The name of the workgroup that contains the named query.
     */
    private $workGroup;

    /**
     * @param array{
     *   Name: string,
     *   Description?: null|string,
     *   Database: string,
     *   QueryString: string,
     *   NamedQueryId?: null|string,
     *   WorkGroup?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->name = $input['Name'] ?? null;
        $this->description = $input['Description'] ?? null;
        $this->database = $input['Database'] ?? null;
        $this->queryString = $input['QueryString'] ?? null;
        $this->namedQueryId = $input['NamedQueryId'] ?? null;
        $this->workGroup = $input['WorkGroup'] ?? null;
    }

    /**
     * @param array{
     *   Name: string,
     *   Description?: null|string,
     *   Database: string,
     *   QueryString: string,
     *   NamedQueryId?: null|string,
     *   WorkGroup?: null|string,
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
}
