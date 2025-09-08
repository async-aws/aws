<?php

namespace AsyncAws\IotData\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

/**
 * The input for the UpdateThingShadow operation.
 */
final class UpdateThingShadowRequest extends Input
{
    /**
     * The name of the thing.
     *
     * @required
     *
     * @var string|null
     */
    private $thingName;

    /**
     * The name of the shadow.
     *
     * @var string|null
     */
    private $shadowName;

    /**
     * The state information, in JSON format.
     *
     * @required
     *
     * @var string|null
     */
    private $payload;

    /**
     * @param array{
     *   thingName?: string,
     *   shadowName?: string|null,
     *   payload?: string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->thingName = $input['thingName'] ?? null;
        $this->shadowName = $input['shadowName'] ?? null;
        $this->payload = $input['payload'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   thingName?: string,
     *   shadowName?: string|null,
     *   payload?: string,
     *   '@region'?: string|null,
     * }|UpdateThingShadowRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getPayload(): ?string
    {
        return $this->payload;
    }

    public function getShadowName(): ?string
    {
        return $this->shadowName;
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
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        // Prepare query
        $query = [];
        if (null !== $this->shadowName) {
            $query['name'] = $this->shadowName;
        }

        // Prepare URI
        $uri = [];
        if (null === $v = $this->thingName) {
            throw new InvalidArgument(\sprintf('Missing parameter "thingName" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['thingName'] = $v;
        $uriString = '/things/' . rawurlencode($uri['thingName']) . '/shadow';

        // Prepare Body
        if (null === $v = $this->payload) {
            throw new InvalidArgument(\sprintf('Missing parameter "payload" for "%s". The value cannot be null.', __CLASS__));
        }
        $body = $v;

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setPayload(?string $value): self
    {
        $this->payload = $value;

        return $this;
    }

    public function setShadowName(?string $value): self
    {
        $this->shadowName = $value;

        return $this;
    }

    public function setThingName(?string $value): self
    {
        $this->thingName = $value;

        return $this;
    }
}
