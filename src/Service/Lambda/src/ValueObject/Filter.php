<?php

namespace AsyncAws\Lambda\ValueObject;

/**
 * A structure within a `FilterCriteria` object that defines an event filtering pattern.
 */
final class Filter
{
    /**
     * A filter pattern. For more information on the syntax of a filter pattern, see Filter rule syntax [^1].
     *
     * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/invocation-eventfiltering.html#filtering-syntax
     *
     * @var string|null
     */
    private $pattern;

    /**
     * @param array{
     *   Pattern?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->pattern = $input['Pattern'] ?? null;
    }

    /**
     * @param array{
     *   Pattern?: string|null,
     * }|Filter $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getPattern(): ?string
    {
        return $this->pattern;
    }
}
