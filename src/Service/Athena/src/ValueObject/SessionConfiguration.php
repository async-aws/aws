<?php

namespace AsyncAws\Athena\ValueObject;

/**
 * Contains the workgroup configuration information used by the session.
 */
final class SessionConfiguration
{
    /**
     * The ARN of the execution role used for the session.
     */
    private $executionRole;

    /**
     * The Amazon S3 location that stores information for the notebook.
     */
    private $workingDirectory;

    /**
     * The idle timeout in seconds for the session.
     */
    private $idleTimeoutSeconds;

    private $encryptionConfiguration;

    /**
     * @param array{
     *   ExecutionRole?: null|string,
     *   WorkingDirectory?: null|string,
     *   IdleTimeoutSeconds?: null|string,
     *   EncryptionConfiguration?: null|EncryptionConfiguration|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->executionRole = $input['ExecutionRole'] ?? null;
        $this->workingDirectory = $input['WorkingDirectory'] ?? null;
        $this->idleTimeoutSeconds = $input['IdleTimeoutSeconds'] ?? null;
        $this->encryptionConfiguration = isset($input['EncryptionConfiguration']) ? EncryptionConfiguration::create($input['EncryptionConfiguration']) : null;
    }

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

    public function getIdleTimeoutSeconds(): ?string
    {
        return $this->idleTimeoutSeconds;
    }

    public function getWorkingDirectory(): ?string
    {
        return $this->workingDirectory;
    }
}
