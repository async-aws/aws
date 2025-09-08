<?php

namespace AsyncAws\AppSync\ValueObject;

/**
 * The Amazon Relational Database Service (Amazon RDS) HTTP endpoint configuration.
 */
final class RdsHttpEndpointConfig
{
    /**
     * Amazon Web Services Region for Amazon RDS HTTP endpoint.
     *
     * @var string|null
     */
    private $awsRegion;

    /**
     * Amazon RDS cluster Amazon Resource Name (ARN).
     *
     * @var string|null
     */
    private $dbClusterIdentifier;

    /**
     * Logical database name.
     *
     * @var string|null
     */
    private $databaseName;

    /**
     * Logical schema name.
     *
     * @var string|null
     */
    private $schema;

    /**
     * Amazon Web Services secret store Amazon Resource Name (ARN) for database credentials.
     *
     * @var string|null
     */
    private $awsSecretStoreArn;

    /**
     * @param array{
     *   awsRegion?: string|null,
     *   dbClusterIdentifier?: string|null,
     *   databaseName?: string|null,
     *   schema?: string|null,
     *   awsSecretStoreArn?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->awsRegion = $input['awsRegion'] ?? null;
        $this->dbClusterIdentifier = $input['dbClusterIdentifier'] ?? null;
        $this->databaseName = $input['databaseName'] ?? null;
        $this->schema = $input['schema'] ?? null;
        $this->awsSecretStoreArn = $input['awsSecretStoreArn'] ?? null;
    }

    /**
     * @param array{
     *   awsRegion?: string|null,
     *   dbClusterIdentifier?: string|null,
     *   databaseName?: string|null,
     *   schema?: string|null,
     *   awsSecretStoreArn?: string|null,
     * }|RdsHttpEndpointConfig $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAwsRegion(): ?string
    {
        return $this->awsRegion;
    }

    public function getAwsSecretStoreArn(): ?string
    {
        return $this->awsSecretStoreArn;
    }

    public function getDatabaseName(): ?string
    {
        return $this->databaseName;
    }

    public function getDbClusterIdentifier(): ?string
    {
        return $this->dbClusterIdentifier;
    }

    public function getSchema(): ?string
    {
        return $this->schema;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->awsRegion) {
            $payload['awsRegion'] = $v;
        }
        if (null !== $v = $this->dbClusterIdentifier) {
            $payload['dbClusterIdentifier'] = $v;
        }
        if (null !== $v = $this->databaseName) {
            $payload['databaseName'] = $v;
        }
        if (null !== $v = $this->schema) {
            $payload['schema'] = $v;
        }
        if (null !== $v = $this->awsSecretStoreArn) {
            $payload['awsSecretStoreArn'] = $v;
        }

        return $payload;
    }
}
