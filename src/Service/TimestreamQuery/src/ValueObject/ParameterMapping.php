<?php

namespace AsyncAws\TimestreamQuery\ValueObject;

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
        $this->name = $input['Name'] ?? null;
        $this->type = isset($input['Type']) ? Type::create($input['Type']) : null;
    }

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
}
