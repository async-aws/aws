<?php

namespace AsyncAws\Iot\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class DeleteThingGroupRequest extends Input
{
    /**
     * The name of the thing group to delete.
     *
     * @required
     *
     * @var string|null
     */
    private $thingGroupName;

    /**
     * The expected version of the thing group to delete.
     *
     * @var int|null
     */
    private $expectedVersion;

    /**
     * @param array{
     *   thingGroupName?: string,
     *   expectedVersion?: null|int,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->thingGroupName = $input['thingGroupName'] ?? null;
        $this->expectedVersion = $input['expectedVersion'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   thingGroupName?: string,
     *   expectedVersion?: null|int,
     *   '@region'?: string|null,
     * }|DeleteThingGroupRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getExpectedVersion(): ?int
    {
        return $this->expectedVersion;
    }

    public function getThingGroupName(): ?string
    {
        return $this->thingGroupName;
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
        if (null !== $this->expectedVersion) {
            $query['expectedVersion'] = (string) $this->expectedVersion;
        }

        // Prepare URI
        $uri = [];
        if (null === $v = $this->thingGroupName) {
            throw new InvalidArgument(sprintf('Missing parameter "thingGroupName" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['thingGroupName'] = $v;
        $uriString = '/thing-groups/' . rawurlencode($uri['thingGroupName']);

        // Prepare Body
        $body = '';

        // Return the Request
        return new Request('DELETE', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setExpectedVersion(?int $value): self
    {
        $this->expectedVersion = $value;

        return $this;
    }

    public function setThingGroupName(?string $value): self
    {
        $this->thingGroupName = $value;

        return $this;
    }
}
