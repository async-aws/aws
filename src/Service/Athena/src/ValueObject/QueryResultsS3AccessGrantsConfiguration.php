<?php

namespace AsyncAws\Athena\ValueObject;

use AsyncAws\Athena\Enum\AuthenticationType;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Specifies whether Amazon S3 access grants are enabled for query results.
 */
final class QueryResultsS3AccessGrantsConfiguration
{
    /**
     * Specifies whether Amazon S3 access grants are enabled for query results.
     *
     * @var bool
     */
    private $enableS3AccessGrants;

    /**
     * When enabled, appends the user ID as an Amazon S3 path prefix to the query result output location.
     *
     * @var bool|null
     */
    private $createUserLevelPrefix;

    /**
     * The authentication type used for Amazon S3 access grants. Currently, only `DIRECTORY_IDENTITY` is supported.
     *
     * @var AuthenticationType::*
     */
    private $authenticationType;

    /**
     * @param array{
     *   EnableS3AccessGrants: bool,
     *   CreateUserLevelPrefix?: bool|null,
     *   AuthenticationType: AuthenticationType::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->enableS3AccessGrants = $input['EnableS3AccessGrants'] ?? $this->throwException(new InvalidArgument('Missing required field "EnableS3AccessGrants".'));
        $this->createUserLevelPrefix = $input['CreateUserLevelPrefix'] ?? null;
        $this->authenticationType = $input['AuthenticationType'] ?? $this->throwException(new InvalidArgument('Missing required field "AuthenticationType".'));
    }

    /**
     * @param array{
     *   EnableS3AccessGrants: bool,
     *   CreateUserLevelPrefix?: bool|null,
     *   AuthenticationType: AuthenticationType::*,
     * }|QueryResultsS3AccessGrantsConfiguration $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return AuthenticationType::*
     */
    public function getAuthenticationType(): string
    {
        return $this->authenticationType;
    }

    public function getCreateUserLevelPrefix(): ?bool
    {
        return $this->createUserLevelPrefix;
    }

    public function getEnableS3AccessGrants(): bool
    {
        return $this->enableS3AccessGrants;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
