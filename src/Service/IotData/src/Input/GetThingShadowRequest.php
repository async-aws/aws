<?php

namespace AsyncAws\IotData\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

/**
 * The input for the GetThingShadow operation.
 */
final class GetThingShadowRequest extends Input
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
     * @param array{
     *   thingName?: string,
     *   shadowName?: string|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->thingName = $input['thingName'] ?? null;
        $this->shadowName = $input['shadowName'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   thingName?: string,
     *   shadowName?: string|null,
     *   '@region'?: string|null,
     * }|GetThingShadowRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
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
        $body = '';

        // Return the Request
        return new Request('GET', $uriString, $query, $headers, StreamFactory::create($body));
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
