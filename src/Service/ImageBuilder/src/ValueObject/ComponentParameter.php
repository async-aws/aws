<?php

namespace AsyncAws\ImageBuilder\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Contains a key/value pair that sets the named component parameter.
 */
final class ComponentParameter
{
    /**
     * The name of the component parameter to set.
     *
     * @var string
     */
    private $name;

    /**
     * Sets the value for the named component parameter.
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
     * }|ComponentParameter $input
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
