<?php

namespace AsyncAws\AppSync\ValueObject;

/**
 * Describes an AppSync error.
 */
final class CodeError
{
    /**
     * The type of code error.
     */
    private $errorType;

    /**
     * A user presentable error.
     */
    private $value;

    /**
     * The line, column, and span location of the error in the code.
     */
    private $location;

    /**
     * @param array{
     *   errorType?: null|string,
     *   value?: null|string,
     *   location?: null|CodeErrorLocation|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->errorType = $input['errorType'] ?? null;
        $this->value = $input['value'] ?? null;
        $this->location = isset($input['location']) ? CodeErrorLocation::create($input['location']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getErrorType(): ?string
    {
        return $this->errorType;
    }

    public function getLocation(): ?CodeErrorLocation
    {
        return $this->location;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }
}
