<?php

namespace AsyncAws\AppSync\ValueObject;

/**
 * Amazon RDS HTTP endpoint settings.
 */
final class RdsHttpEndpointConfig
{
    /**
     * Amazon Web Services Region for RDS HTTP endpoint.
     */
    private $awsRegion;

    /**
     * Amazon RDS cluster ARN.
     */
    private $dbClusterIdentifier;

    /**
     * Logical database name.
     */
    private $databaseName;

    /**
     * Logical schema name.
     */
    private $schema;

    /**
     * Amazon Web Services secret store ARN for database credentials.
     */
    private $awsSecretStoreArn;

    /**
     * @param array{
     *   awsRegion?: null|string,
     *   dbClusterIdentifier?: null|string,
     *   databaseName?: null|string,
     *   schema?: null|string,
     *   awsSecretStoreArn?: null|string,
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
