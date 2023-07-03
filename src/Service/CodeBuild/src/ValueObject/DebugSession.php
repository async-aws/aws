<?php

namespace AsyncAws\CodeBuild\ValueObject;

/**
 * Contains information about the debug session for a build. For more information, see Viewing a running build in
 * Session Manager [^1].
 *
 * [^1]: https://docs.aws.amazon.com/codebuild/latest/userguide/session-manager.html
 */
final class DebugSession
{
    /**
     * Specifies if session debugging is enabled for this build.
     *
     * @var bool|null
     */
    private $sessionEnabled;

    /**
     * Contains the identifier of the Session Manager session used for the build. To work with the paused build, you open
     * this session to examine, control, and resume the build.
     *
     * @var string|null
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

    /**
     * @param array{
     *   sessionEnabled?: null|bool,
     *   sessionTarget?: null|string,
     * }|DebugSession $input
     */
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
