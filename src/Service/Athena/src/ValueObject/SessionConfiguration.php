<?php

namespace AsyncAws\Athena\ValueObject;

/**
 * Contains session configuration information.
 */
final class SessionConfiguration
{
    /**
     * The ARN of the execution role used to access user resources for Spark sessions and Identity Center enabled
     * workgroups. This property applies only to Spark enabled workgroups and Identity Center enabled workgroups.
     *
     * @var string|null
     */
    private $executionRole;

    /**
     * The Amazon S3 location that stores information for the notebook.
     *
     * @var string|null
     */
    private $workingDirectory;

    /**
     * The idle timeout in seconds for the session.
     *
     * @var int|null
     */
    private $idleTimeoutSeconds;

    /**
     * The idle timeout in seconds for the session.
     *
     * @var int|null
     */
    private $sessionIdleTimeoutInMinutes;

    /**
     * @var EncryptionConfiguration|null
     */
    private $encryptionConfiguration;

    /**
     * @param array{
     *   ExecutionRole?: string|null,
     *   WorkingDirectory?: string|null,
     *   IdleTimeoutSeconds?: int|null,
     *   SessionIdleTimeoutInMinutes?: int|null,
     *   EncryptionConfiguration?: EncryptionConfiguration|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->executionRole = $input['ExecutionRole'] ?? null;
        $this->workingDirectory = $input['WorkingDirectory'] ?? null;
        $this->idleTimeoutSeconds = $input['IdleTimeoutSeconds'] ?? null;
        $this->sessionIdleTimeoutInMinutes = $input['SessionIdleTimeoutInMinutes'] ?? null;
        $this->encryptionConfiguration = isset($input['EncryptionConfiguration']) ? EncryptionConfiguration::create($input['EncryptionConfiguration']) : null;
    }

    /**
     * @param array{
     *   ExecutionRole?: string|null,
     *   WorkingDirectory?: string|null,
     *   IdleTimeoutSeconds?: int|null,
     *   SessionIdleTimeoutInMinutes?: int|null,
     *   EncryptionConfiguration?: EncryptionConfiguration|array|null,
     * }|SessionConfiguration $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getEncryptionConfiguration(): ?EncryptionConfiguration
    {
        return $this->encryptionConfiguration;
    }

    public function getExecutionRole(): ?string
    {
        return $this->executionRole;
    }

    public function getIdleTimeoutSeconds(): ?int
    {
        return $this->idleTimeoutSeconds;
    }

    public function getSessionIdleTimeoutInMinutes(): ?int
    {
        return $this->sessionIdleTimeoutInMinutes;
    }

    public function getWorkingDirectory(): ?string
    {
        return $this->workingDirectory;
    }
}
