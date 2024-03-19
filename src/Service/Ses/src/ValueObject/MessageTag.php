<?php

namespace AsyncAws\Ses\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Contains the name and value of a tag that you apply to an email. You can use message tags when you publish email
 * sending events.
 */
final class MessageTag
{
    /**
     * The name of the message tag. The message tag name has to meet the following criteria:
     *
     * - It can only contain ASCII letters (a–z, A–Z), numbers (0–9), underscores (_), or dashes (-).
     * - It can contain no more than 256 characters.
     *
     * @var string
     */
    private $name;

    /**
     * The value of the message tag. The message tag value has to meet the following criteria:
     *
     * - It can only contain ASCII letters (a–z, A–Z), numbers (0–9), underscores (_), or dashes (-).
     * - It can contain no more than 256 characters.
     *
     * @var string
     */
    private $value;

    /**
     * @param array{
     *   Name: string,
     *   Value: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->name = $input['Name'] ?? $this->throwException(new InvalidArgument('Missing required field "Name".'));
        $this->value = $input['Value'] ?? $this->throwException(new InvalidArgument('Missing required field "Value".'));
    }

    /**
     * @param array{
     *   Name: string,
     *   Value: string,
     * }|MessageTag $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->name;
        $payload['Name'] = $v;
        $v = $this->value;
        $payload['Value'] = $v;

        return $payload;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
