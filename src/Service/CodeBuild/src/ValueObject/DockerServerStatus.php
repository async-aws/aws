<?php

namespace AsyncAws\CodeBuild\ValueObject;

/**
 * Contains information about the status of the docker server.
 */
final class DockerServerStatus
{
    /**
     * The status of the docker server.
     *
     * @var string|null
     */
    private $status;

    /**
     * A message associated with the status of a docker server.
     *
     * @var string|null
     */
    private $message;

    /**
     * @param array{
     *   status?: null|string,
     *   message?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->status = $input['status'] ?? null;
        $this->message = $input['message'] ?? null;
    }

    /**
     * @param array{
     *   status?: null|string,
     *   message?: null|string,
     * }|DockerServerStatus $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }
}
