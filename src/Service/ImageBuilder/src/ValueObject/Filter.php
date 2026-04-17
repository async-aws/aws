<?php

namespace AsyncAws\ImageBuilder\ValueObject;

/**
 * A filter name and value pair that is used to return a more specific list of results from a list operation. Filters
 * can be used to match a set of resources by specific criteria, such as tags, attributes, or IDs.
 */
final class Filter
{
    /**
     * The name of the filter. Filter names are case-sensitive.
     *
     * @var string|null
     */
    private $name;

    /**
     * The filter values. Filter values are case-sensitive.
     *
     * @var string[]|null
     */
    private $values;

    /**
     * @param array{
     *   name?: string|null,
     *   values?: string[]|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->name = $input['name'] ?? null;
        $this->values = $input['values'] ?? null;
    }

    /**
     * @param array{
     *   name?: string|null,
     *   values?: string[]|null,
     * }|Filter $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string[]
     */
    public function getValues(): array
    {
        return $this->values ?? [];
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->name) {
            $payload['name'] = $v;
        }
        if (null !== $v = $this->values) {
            $index = -1;
            $payload['values'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['values'][$index] = $listValue;
            }
        }

        return $payload;
    }
}
