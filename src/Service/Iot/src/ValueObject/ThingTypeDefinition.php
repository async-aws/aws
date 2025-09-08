<?php

namespace AsyncAws\Iot\ValueObject;

/**
 * The definition of the thing type, including thing type name and description.
 */
final class ThingTypeDefinition
{
    /**
     * The name of the thing type.
     *
     * @var string|null
     */
    private $thingTypeName;

    /**
     * The thing type ARN.
     *
     * @var string|null
     */
    private $thingTypeArn;

    /**
     * The ThingTypeProperties for the thing type.
     *
     * @var ThingTypeProperties|null
     */
    private $thingTypeProperties;

    /**
     * The ThingTypeMetadata contains additional information about the thing type including: creation date and time, a value
     * indicating whether the thing type is deprecated, and a date and time when it was deprecated.
     *
     * @var ThingTypeMetadata|null
     */
    private $thingTypeMetadata;

    /**
     * @param array{
     *   thingTypeName?: string|null,
     *   thingTypeArn?: string|null,
     *   thingTypeProperties?: ThingTypeProperties|array|null,
     *   thingTypeMetadata?: ThingTypeMetadata|array|null,
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
     *   thingTypeName?: string|null,
     *   thingTypeArn?: string|null,
     *   thingTypeProperties?: ThingTypeProperties|array|null,
     *   thingTypeMetadata?: ThingTypeMetadata|array|null,
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
