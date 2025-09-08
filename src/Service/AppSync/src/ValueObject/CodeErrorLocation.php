<?php

namespace AsyncAws\AppSync\ValueObject;

/**
 * Describes the location of the error in a code sample.
 */
final class CodeErrorLocation
{
    /**
     * The line number in the code. Defaults to `0` if unknown.
     *
     * @var int|null
     */
    private $line;

    /**
     * The column number in the code. Defaults to `0` if unknown.
     *
     * @var int|null
     */
    private $column;

    /**
     * The span/length of the error. Defaults to `-1` if unknown.
     *
     * @var int|null
     */
    private $span;

    /**
     * @param array{
     *   line?: int|null,
     *   column?: int|null,
     *   span?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->line = $input['line'] ?? null;
        $this->column = $input['column'] ?? null;
        $this->span = $input['span'] ?? null;
    }

    /**
     * @param array{
     *   line?: int|null,
     *   column?: int|null,
     *   span?: int|null,
     * }|CodeErrorLocation $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getColumn(): ?int
    {
        return $this->column;
    }

    public function getLine(): ?int
    {
        return $this->line;
    }

    public function getSpan(): ?int
    {
        return $this->span;
    }
}
