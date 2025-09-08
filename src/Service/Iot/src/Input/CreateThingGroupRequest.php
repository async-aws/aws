<?php

namespace AsyncAws\Iot\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Iot\ValueObject\Tag;
use AsyncAws\Iot\ValueObject\ThingGroupProperties;

final class CreateThingGroupRequest extends Input
{
    /**
     * The thing group name to create.
     *
     * @required
     *
     * @var string|null
     */
    private $thingGroupName;

    /**
     * The name of the parent thing group.
     *
     * @var string|null
     */
    private $parentGroupName;

    /**
     * The thing group properties.
     *
     * @var ThingGroupProperties|null
     */
    private $thingGroupProperties;

    /**
     * Metadata which can be used to manage the thing group.
     *
     * @var Tag[]|null
     */
    private $tags;

    /**
     * @param array{
     *   thingGroupName?: string,
     *   parentGroupName?: string|null,
     *   thingGroupProperties?: ThingGroupProperties|array|null,
     *   tags?: array<Tag|array>|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->thingGroupName = $input['thingGroupName'] ?? null;
        $this->parentGroupName = $input['parentGroupName'] ?? null;
        $this->thingGroupProperties = isset($input['thingGroupProperties']) ? ThingGroupProperties::create($input['thingGroupProperties']) : null;
        $this->tags = isset($input['tags']) ? array_map([Tag::class, 'create'], $input['tags']) : null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   thingGroupName?: string,
     *   parentGroupName?: string|null,
     *   thingGroupProperties?: ThingGroupProperties|array|null,
     *   tags?: array<Tag|array>|null,
     *   '@region'?: string|null,
     * }|CreateThingGroupRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getParentGroupName(): ?string
    {
        return $this->parentGroupName;
    }

    /**
     * @return Tag[]
     */
    public function getTags(): array
    {
        return $this->tags ?? [];
    }

    public function getThingGroupName(): ?string
    {
        return $this->thingGroupName;
    }

    public function getThingGroupProperties(): ?ThingGroupProperties
    {
        return $this->thingGroupProperties;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        // Prepare query
        $query = [];

        // Prepare URI
        $uri = [];
        if (null === $v = $this->thingGroupName) {
            throw new InvalidArgument(\sprintf('Missing parameter "thingGroupName" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['thingGroupName'] = $v;
        $uriString = '/thing-groups/' . rawurlencode($uri['thingGroupName']);

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setParentGroupName(?string $value): self
    {
        $this->parentGroupName = $value;

        return $this;
    }

    /**
     * @param Tag[] $value
     */
    public function setTags(array $value): self
    {
        $this->tags = $value;

        return $this;
    }

    public function setThingGroupName(?string $value): self
    {
        $this->thingGroupName = $value;

        return $this;
    }

    public function setThingGroupProperties(?ThingGroupProperties $value): self
    {
        $this->thingGroupProperties = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];

        if (null !== $v = $this->parentGroupName) {
            $payload['parentGroupName'] = $v;
        }
        if (null !== $v = $this->thingGroupProperties) {
            $payload['thingGroupProperties'] = $v->requestBody();
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
