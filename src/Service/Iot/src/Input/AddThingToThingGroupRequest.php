<?php

namespace AsyncAws\Iot\Input;

use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class AddThingToThingGroupRequest extends Input
{
    /**
     * The name of the group to which you are adding a thing.
     *
     * @var string|null
     */
    private $thingGroupName;

    /**
     * The ARN of the group to which you are adding a thing.
     *
     * @var string|null
     */
    private $thingGroupArn;

    /**
     * The name of the thing to add to a group.
     *
     * @var string|null
     */
    private $thingName;

    /**
     * The ARN of the thing to add to a group.
     *
     * @var string|null
     */
    private $thingArn;

    /**
     * Override dynamic thing groups with static thing groups when 10-group limit is reached. If a thing belongs to 10 thing
     * groups, and one or more of those groups are dynamic thing groups, adding a thing to a static group removes the thing
     * from the last dynamic group.
     *
     * @var bool|null
     */
    private $overrideDynamicGroups;

    /**
     * @param array{
     *   thingGroupName?: null|string,
     *   thingGroupArn?: null|string,
     *   thingName?: null|string,
     *   thingArn?: null|string,
     *   overrideDynamicGroups?: null|bool,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->thingGroupName = $input['thingGroupName'] ?? null;
        $this->thingGroupArn = $input['thingGroupArn'] ?? null;
        $this->thingName = $input['thingName'] ?? null;
        $this->thingArn = $input['thingArn'] ?? null;
        $this->overrideDynamicGroups = $input['overrideDynamicGroups'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   thingGroupName?: null|string,
     *   thingGroupArn?: null|string,
     *   thingName?: null|string,
     *   thingArn?: null|string,
     *   overrideDynamicGroups?: null|bool,
     *   '@region'?: string|null,
     * }|AddThingToThingGroupRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getOverrideDynamicGroups(): ?bool
    {
        return $this->overrideDynamicGroups;
    }

    public function getThingArn(): ?string
    {
        return $this->thingArn;
    }

    public function getThingGroupArn(): ?string
    {
        return $this->thingGroupArn;
    }

    public function getThingGroupName(): ?string
    {
        return $this->thingGroupName;
    }

    public function getThingName(): ?string
    {
        return $this->thingName;
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
        $uriString = '/thing-groups/addThingToThingGroup';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('PUT', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setOverrideDynamicGroups(?bool $value): self
    {
        $this->overrideDynamicGroups = $value;

        return $this;
    }

    public function setThingArn(?string $value): self
    {
        $this->thingArn = $value;

        return $this;
    }

    public function setThingGroupArn(?string $value): self
    {
        $this->thingGroupArn = $value;

        return $this;
    }

    public function setThingGroupName(?string $value): self
    {
        $this->thingGroupName = $value;

        return $this;
    }

    public function setThingName(?string $value): self
    {
        $this->thingName = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->thingGroupName) {
            $payload['thingGroupName'] = $v;
        }
        if (null !== $v = $this->thingGroupArn) {
            $payload['thingGroupArn'] = $v;
        }
        if (null !== $v = $this->thingName) {
            $payload['thingName'] = $v;
        }
        if (null !== $v = $this->thingArn) {
            $payload['thingArn'] = $v;
        }
        if (null !== $v = $this->overrideDynamicGroups) {
            $payload['overrideDynamicGroups'] = (bool) $v;
        }

        return $payload;
    }
}
