<?php

namespace AsyncAws\LocationService\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * The input failed to meet the constraints specified by the AWS service in a specified field.
 */
final class ValidationExceptionField
{
    /**
     * A message with the reason for the validation exception error.
     *
     * @var string
     */
    private $message;

    /**
     * The field name where the invalid entry was detected.
     *
     * @var string
     */
    private $name;

    /**
     * @param array{
     *   Message: string,
     *   Name: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->message = $input['Message'] ?? $this->throwException(new InvalidArgument('Missing required field "Message".'));
        $this->name = $input['Name'] ?? $this->throwException(new InvalidArgument('Missing required field "Name".'));
    }

    /**
     * @param array{
     *   Message: string,
     *   Name: string,
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
