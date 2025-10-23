<?php

namespace AsyncAws\Iot\ValueObject;

/**
 * The properties of the thing, including thing name, thing type name, and a list of thing attributes.
 */
final class ThingAttribute
{
    /**
     * The name of the thing.
     *
     * @var string|null
     */
    private $thingName;

    /**
     * The name of the thing type, if the thing has been associated with a type.
     *
     * @var string|null
     */
    private $thingTypeName;

    /**
     * The thing ARN.
     *
     * @var string|null
     */
    private $thingArn;

    /**
     * A list of thing attributes which are name-value pairs.
     *
     * @var array<string, string>|null
     */
    private $attributes;

    /**
     * The version of the thing record in the registry.
     *
     * @var int|null
     */
    private $version;

    /**
     * @param array{
     *   thingName?: string|null,
     *   thingTypeName?: string|null,
     *   thingArn?: string|null,
     *   attributes?: array<string, string>|null,
     *   version?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->thingName = $input['thingName'] ?? null;
        $this->thingTypeName = $input['thingTypeName'] ?? null;
        $this->thingArn = $input['thingArn'] ?? null;
        $this->attributes = $input['attributes'] ?? null;
        $this->version = $input['version'] ?? null;
    }

    /**
     * @param array{
     *   thingName?: string|null,
     *   thingTypeName?: string|null,
     *   thingArn?: string|null,
     *   attributes?: array<string, string>|null,
     *   version?: int|null,
     * }|ThingAttribute $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return array<string, string>
     */
    public function getAttributes(): array
    {
        return $this->attributes ?? [];
    }

    public function getThingArn(): ?string
    {
        return $this->thingArn;
    }

    public function getThingName(): ?string
    {
        return $this->thingName;
    }

    public function getThingTypeName(): ?string
    {
        return $this->thingTypeName;
    }

    public function getVersion(): ?int
    {
        return $this->version;
    }
}
