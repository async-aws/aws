<?php

namespace AsyncAws\Iot\ValueObject;

/**
 * The definition of the thing type, including thing type name and description.
 */
final class ThingTypeDefinition
{
    /**
     * The name of the thing type.
     */
    private $thingTypeName;

    /**
     * The thing type ARN.
     */
    private $thingTypeArn;

    /**
     * The ThingTypeProperties for the thing type.
     */
    private $thingTypeProperties;

    /**
     * The ThingTypeMetadata contains additional information about the thing type including: creation date and time, a value
     * indicating whether the thing type is deprecated, and a date and time when it was deprecated.
     */
    private $thingTypeMetadata;

    /**
     * @param array{
     *   thingTypeName?: null|string,
     *   thingTypeArn?: null|string,
     *   thingTypeProperties?: null|ThingTypeProperties|array,
     *   thingTypeMetadata?: null|ThingTypeMetadata|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->thingTypeName = $input['thingTypeName'] ?? null;
        $this->thingTypeArn = $input['thingTypeArn'] ?? null;
        $this->thingTypeProperties = isset($input['thingTypeProperties']) ? ThingTypeProperties::create($input['thingTypeProperties']) : null;
        $this->thingTypeMetadata = isset($input['thingTypeMetadata']) ? ThingTypeMetadata::create($input['thingTypeMetadata']) : null;
    }

    /**
     * @param array{
     *   thingTypeName?: null|string,
     *   thingTypeArn?: null|string,
     *   thingTypeProperties?: null|ThingTypeProperties|array,
     *   thingTypeMetadata?: null|ThingTypeMetadata|array,
     * }|ThingTypeDefinition $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getThingTypeArn(): ?string
    {
        return $this->thingTypeArn;
    }

    public function getThingTypeMetadata(): ?ThingTypeMetadata
    {
        return $this->thingTypeMetadata;
    }

    public function getThingTypeName(): ?string
    {
        return $this->thingTypeName;
    }

    public function getThingTypeProperties(): ?ThingTypeProperties
    {
        return $this->thingTypeProperties;
    }
}
