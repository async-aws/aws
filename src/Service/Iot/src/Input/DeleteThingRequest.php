<?php

namespace AsyncAws\Iot\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

/**
 * The input for the DeleteThing operation.
 */
final class DeleteThingRequest extends Input
{
    /**
     * The name of the thing to delete.
     *
     * @required
     *
     * @var string|null
     */
    private $thingName;

    /**
     * The expected version of the thing record in the registry. If the version of the record in the registry does not match
     * the expected version specified in the request, the `DeleteThing` request is rejected with a
     * `VersionConflictException`.
     *
     * @var int|null
     */
    private $expectedVersion;

    /**
     * @param array{
     *   thingName?: string,
     *   expectedVersion?: int,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->thingName = $input['thingName'] ?? null;
        $this->expectedVersion = $input['expectedVersion'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   thingName?: string,
     *   expectedVersion?: int,
     *   '@region'?: string|null,
     * }|DeleteThingRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getExpectedVersion(): ?int
    {
        return $this->expectedVersion;
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
        if (null !== $this->expectedVersion) {
            $query['expectedVersion'] = (string) $this->expectedVersion;
        }

        // Prepare URI
        $uri = [];
        if (null === $v = $this->thingName) {
            throw new InvalidArgument(sprintf('Missing parameter "thingName" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['thingName'] = $v;
        $uriString = '/things/' . rawurlencode($uri['thingName']);

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

    public function setThingName(?string $value): self
    {
        $this->thingName = $value;

        return $this;
    }
}
