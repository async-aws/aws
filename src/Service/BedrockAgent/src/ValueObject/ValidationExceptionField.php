<?php

namespace AsyncAws\BedrockAgent\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Stores information about a field passed inside a request that resulted in an validation error.
 */
final class ValidationExceptionField
{
    /**
     * The name of the field.
     *
     * @var string
     */
    private $name;

    /**
     * A message describing why this field failed validation.
     *
     * @var string
     */
    private $message;

    /**
     * @param array{
     *   name: string,
     *   message: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->name = $input['name'] ?? $this->throwException(new InvalidArgument('Missing required field "name".'));
        $this->message = $input['message'] ?? $this->throwException(new InvalidArgument('Missing required field "message".'));
    }

    /**
     * @param array{
     *   name: string,
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

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
