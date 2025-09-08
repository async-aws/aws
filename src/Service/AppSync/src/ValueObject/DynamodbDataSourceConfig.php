<?php

namespace AsyncAws\AppSync\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Describes an Amazon DynamoDB data source configuration.
 */
final class DynamodbDataSourceConfig
{
    /**
     * The table name.
     *
     * @var string
     */
    private $tableName;

    /**
     * The Amazon Web Services Region.
     *
     * @var string
     */
    private $awsRegion;

    /**
     * Set to TRUE to use Amazon Cognito credentials with this data source.
     *
     * @var bool|null
     */
    private $useCallerCredentials;

    /**
     * The `DeltaSyncConfig` for a versioned data source.
     *
     * @var DeltaSyncConfig|null
     */
    private $deltaSyncConfig;

    /**
     * Set to TRUE to use Conflict Detection and Resolution with this data source.
     *
     * @var bool|null
     */
    private $versioned;

    /**
     * @param array{
     *   tableName: string,
     *   awsRegion: string,
     *   useCallerCredentials?: bool|null,
     *   deltaSyncConfig?: DeltaSyncConfig|array|null,
     *   versioned?: bool|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->tableName = $input['tableName'] ?? $this->throwException(new InvalidArgument('Missing required field "tableName".'));
        $this->awsRegion = $input['awsRegion'] ?? $this->throwException(new InvalidArgument('Missing required field "awsRegion".'));
        $this->useCallerCredentials = $input['useCallerCredentials'] ?? null;
        $this->deltaSyncConfig = isset($input['deltaSyncConfig']) ? DeltaSyncConfig::create($input['deltaSyncConfig']) : null;
        $this->versioned = $input['versioned'] ?? null;
    }

    /**
     * @param array{
     *   tableName: string,
     *   awsRegion: string,
     *   useCallerCredentials?: bool|null,
     *   deltaSyncConfig?: DeltaSyncConfig|array|null,
     *   versioned?: bool|null,
     * }|DynamodbDataSourceConfig $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAwsRegion(): string
    {
        return $this->awsRegion;
    }

    public function getDeltaSyncConfig(): ?DeltaSyncConfig
    {
        return $this->deltaSyncConfig;
    }

    public function getTableName(): string
    {
        return $this->tableName;
    }

    public function getUseCallerCredentials(): ?bool
    {
        return $this->useCallerCredentials;
    }

    public function getVersioned(): ?bool
    {
        return $this->versioned;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->tableName;
        $payload['tableName'] = $v;
        $v = $this->awsRegion;
        $payload['awsRegion'] = $v;
        if (null !== $v = $this->useCallerCredentials) {
            $payload['useCallerCredentials'] = (bool) $v;
        }
        if (null !== $v = $this->deltaSyncConfig) {
            $payload['deltaSyncConfig'] = $v->requestBody();
        }
        if (null !== $v = $this->versioned) {
            $payload['versioned'] = (bool) $v;
        }

        return $payload;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
