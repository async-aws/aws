<?php

namespace AsyncAws\CodeBuild\ValueObject;

/**
 * Contains information about the debug session for this build.
 */
final class DebugSession
{
    /**
     * Specifies if session debugging is enabled for this build.
     */
    private $sessionEnabled;

    /**
     * Contains the identifier of the Session Manager session used for the build. To work with the paused build, you open
     * this session to examine, control, and resume the build.
     */
    private $sessionTarget;

    /**
     * @param array{
     *   sessionEnabled?: null|bool,
     *   sessionTarget?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->sessionEnabled = $input['sessionEnabled'] ?? null;
        $this->sessionTarget = $input['sessionTarget'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getSessionEnabled(): ?bool
    {
        return $this->sessionEnabled;
    }

    public function getSessionTarget(): ?string
    {
        return $this->sessionTarget;
    }
}
