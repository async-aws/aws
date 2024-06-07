<?php

namespace AsyncAws\LocationService\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * The input failed to meet the constraints specified by the AWS service in a specified field.
 */
final class ValidationExceptionField
{
    /**
     * The field name where the invalid entry was detected.
     *
     * @var string
     */
    private $name;

    /**
     * A message with the reason for the validation exception error.
     *
     * @var string
     */
    private $message;

    /**
     * @param array{
     *   Name: string,
     *   Message: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->name = $input['Name'] ?? $this->throwException(new InvalidArgument('Missing required field "Name".'));
        $this->message = $input['Message'] ?? $this->throwException(new InvalidArgument('Missing required field "Message".'));
    }

    /**
     * @param array{
     *   Name: string,
     *   Message: string,
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
