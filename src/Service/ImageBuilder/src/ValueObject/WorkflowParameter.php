<?php

namespace AsyncAws\ImageBuilder\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Contains a key/value pair that sets the named workflow parameter.
 */
final class WorkflowParameter
{
    /**
     * The name of the workflow parameter to set.
     *
     * @var string
     */
    private $name;

    /**
     * Sets the value for the named workflow parameter.
     *
     * @var string[]
     */
    private $value;

    /**
     * @param array{
     *   name: string,
     *   value: string[],
     * } $input
     */
    public function __construct(array $input)
    {
        $this->name = $input['name'] ?? $this->throwException(new InvalidArgument('Missing required field "name".'));
        $this->value = $input['value'] ?? $this->throwException(new InvalidArgument('Missing required field "value".'));
    }

    /**
     * @param array{
     *   name: string,
     *   value: string[],
     * }|WorkflowParameter $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string[]
     */
    public function getValue(): array
    {
        return $this->value;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
