<?php

namespace AsyncAws\TimestreamQuery\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Mapping for named parameters.
 */
final class ParameterMapping
{
    /**
     * Parameter name.
     */
    private $name;

    private $type;

    /**
     * @param array{
     *   Name: string,
     *   Type: Type|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->name = $input['Name'] ?? $this->throwException(new InvalidArgument('Missing required field "Name".'));
        $this->type = isset($input['Type']) ? Type::create($input['Type']) : $this->throwException(new InvalidArgument('Missing required field "Type".'));
    }

    /**
     * @param array{
     *   Name: string,
     *   Type: Type|array,
     * }|ParameterMapping $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): Type
    {
        return $this->type;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
