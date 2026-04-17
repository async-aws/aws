<?php

namespace AsyncAws\Lambda\ValueObject;

/**
 * An object that contains the filters for an event source.
 */
final class FilterCriteria
{
    /**
     * A list of filters.
     *
     * @var Filter[]|null
     */
    private $filters;

    /**
     * @param array{
     *   Filters?: array<Filter|array>|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->filters = isset($input['Filters']) ? array_map([Filter::class, 'create'], $input['Filters']) : null;
    }

    /**
     * @param array{
     *   Filters?: array<Filter|array>|null,
     * }|FilterCriteria $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return Filter[]
     */
    public function getFilters(): array
    {
        return $this->filters ?? [];
    }
}
