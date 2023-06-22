<?php

namespace AsyncAws\Iot\ValueObject;

/**
 * The ThingTypeProperties contains information about the thing type including: a thing type description, and a list of
 * searchable thing attribute names.
 */
final class ThingTypeProperties
{
    /**
     * The description of the thing type.
     */
    private $thingTypeDescription;

    /**
     * A list of searchable thing attribute names.
     */
    private $searchableAttributes;

    /**
     * @param array{
     *   thingTypeDescription?: null|string,
     *   searchableAttributes?: null|string[],
     * } $input
     */
    public function __construct(array $input)
    {
        $this->thingTypeDescription = $input['thingTypeDescription'] ?? null;
        $this->searchableAttributes = $input['searchableAttributes'] ?? null;
    }

    /**
     * @param array{
     *   thingTypeDescription?: null|string,
     *   searchableAttributes?: null|string[],
     * }|ThingTypeProperties $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return string[]
     */
    public function getSearchableAttributes(): array
    {
        return $this->searchableAttributes ?? [];
    }

    public function getThingTypeDescription(): ?string
    {
        return $this->thingTypeDescription;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->thingTypeDescription) {
            $payload['thingTypeDescription'] = $v;
        }
        if (null !== $v = $this->searchableAttributes) {
            $index = -1;
            $payload['searchableAttributes'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['searchableAttributes'][$index] = $listValue;
            }
        }

        return $payload;
    }
}
