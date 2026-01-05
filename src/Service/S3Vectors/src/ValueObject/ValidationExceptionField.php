<?php

namespace AsyncAws\S3Vectors\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Contains information about a validation exception.
 */
final class ValidationExceptionField
{
    /**
     * A path about the validation exception.
     *
     * @var string
     */
    private $path;

    /**
     * A message about the validation exception.
     *
     * @var string
     */
    private $message;

    /**
     * @param array{
     *   path: string,
     *   message: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->path = $input['path'] ?? $this->throwException(new InvalidArgument('Missing required field "path".'));
        $this->message = $input['message'] ?? $this->throwException(new InvalidArgument('Missing required field "message".'));
    }

    /**
     * @param array{
     *   path: string,
     *   message: string,
     * }|ValidationExceptionField $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
