<?php

namespace AsyncAws\Iot\ValueObject;

/**
 * The properties of the thing, including thing name, thing type name, and a list of thing attributes.
 */
final class ThingAttribute
{
    /**
     * The name of the thing.
     */
    private $thingName;

    /**
     * The name of the thing type, if the thing has been associated with a type.
     */
    private $thingTypeName;

    /**
     * The thing ARN.
     */
    private $thingArn;

    /**
     * A list of thing attributes which are name-value pairs.
     */
    private $attributes;

    /**
     * The version of the thing record in the registry.
     */
    private $version;

    /**
     * @param array{
     *   thingName?: null|string,
     *   thingTypeName?: null|string,
     *   thingArn?: null|string,
     *   attributes?: null|array<string, string>,
     *   version?: null|string,
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
     *   thingName?: null|string,
     *   thingTypeName?: null|string,
     *   thingArn?: null|string,
     *   attributes?: null|array<string, string>,
     *   version?: null|string,
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

    public function getVersion(): ?string
    {
        return $this->version;
    }
}
