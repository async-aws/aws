<?php

namespace AsyncAws\CodeBuild\ValueObject;

/**
 * Additional information about a build phase that has an error. You can use this information for troubleshooting.
 */
final class PhaseContext
{
    /**
     * The status code for the context of the build phase.
     */
    private $statusCode;

    /**
     * An explanation of the build phase's context. This might include a command ID and an exit code.
     */
    private $message;

    /**
     * @param array{
     *   statusCode?: null|string,
     *   message?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->statusCode = $input['statusCode'] ?? null;
        $this->message = $input['message'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function getStatusCode(): ?string
    {
        return $this->statusCode;
    }
}
