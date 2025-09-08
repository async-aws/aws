<?php

namespace AsyncAws\AppSync\ValueObject;

/**
 * Describes an AppSync error.
 */
final class CodeError
{
    /**
     * The type of code error.
     *
     * Examples include, but aren't limited to: `LINT_ERROR`, `PARSER_ERROR`.
     *
     * @var string|null
     */
    private $errorType;

    /**
     * A user presentable error.
     *
     * Examples include, but aren't limited to: `Parsing error: Unterminated string literal`.
     *
     * @var string|null
     */
    private $value;

    /**
     * The line, column, and span location of the error in the code.
     *
     * @var CodeErrorLocation|null
     */
    private $location;

    /**
     * @param array{
     *   errorType?: string|null,
     *   value?: string|null,
     *   location?: CodeErrorLocation|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->errorType = $input['errorType'] ?? null;
        $this->value = $input['value'] ?? null;
        $this->location = isset($input['location']) ? CodeErrorLocation::create($input['location']) : null;
    }

    /**
     * @param array{
     *   errorType?: string|null,
     *   value?: string|null,
     *   location?: CodeErrorLocation|array|null,
     * }|CodeError $input
     */
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
