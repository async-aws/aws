<?php

namespace AsyncAws\Iot\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Iot\ValueObject\Tag;
use AsyncAws\Iot\ValueObject\ThingTypeProperties;

/**
 * The input for the CreateThingType operation.
 */
final class CreateThingTypeRequest extends Input
{
    /**
     * The name of the thing type.
     *
     * @required
     *
     * @var string|null
     */
    private $thingTypeName;

    /**
     * The ThingTypeProperties for the thing type to create. It contains information about the new thing type including a
     * description, and a list of searchable thing attribute names.
     *
     * @var ThingTypeProperties|null
     */
    private $thingTypeProperties;

    /**
     * Metadata which can be used to manage the thing type.
     *
     * @var Tag[]|null
     */
    private $tags;

    /**
     * @param array{
     *   thingTypeName?: string,
     *   thingTypeProperties?: ThingTypeProperties|array,
     *   tags?: Tag[],
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->thingTypeName = $input['thingTypeName'] ?? null;
        $this->thingTypeProperties = isset($input['thingTypeProperties']) ? ThingTypeProperties::create($input['thingTypeProperties']) : null;
        $this->tags = isset($input['tags']) ? array_map([Tag::class, 'create'], $input['tags']) : null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   thingTypeName?: string,
     *   thingTypeProperties?: ThingTypeProperties|array,
     *   tags?: Tag[],
     *   '@region'?: string|null,
     * }|CreateThingTypeRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return Tag[]
     */
    public function getTags(): array
    {
        return $this->tags ?? [];
    }

    public function getThingTypeName(): ?string
    {
        return $this->thingTypeName;
    }

    public function getThingTypeProperties(): ?ThingTypeProperties
    {
        return $this->thingTypeProperties;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = ['content-type' => 'application/json'];

        // Prepare query
        $query = [];

        // Prepare URI
        $uri = [];
        if (null === $v = $this->thingTypeName) {
            throw new InvalidArgument(sprintf('Missing parameter "thingTypeName" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['thingTypeName'] = $v;
        $uriString = '/thing-types/' . rawurlencode($uri['thingTypeName']);

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    /**
     * @param Tag[] $value
     */
    public function setTags(array $value): self
    {
        $this->tags = $value;

        return $this;
    }

    public function setThingTypeName(?string $value): self
    {
        $this->thingTypeName = $value;

        return $this;
    }

    public function setThingTypeProperties(?ThingTypeProperties $value): self
    {
        $this->thingTypeProperties = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];

        if (null !== $v = $this->thingTypeProperties) {
            $payload['thingTypeProperties'] = $v->requestBody();
        }
        if (null !== $v = $this->tags) {
            $index = -1;
            $payload['tags'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['tags'][$index] = $listValue->requestBody();
            }
        }

        return $payload;
    }
}
