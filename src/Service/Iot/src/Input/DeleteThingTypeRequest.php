<?php

namespace AsyncAws\Iot\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

/**
 * The input for the DeleteThingType operation.
 */
final class DeleteThingTypeRequest extends Input
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
     * @param array{
     *   thingTypeName?: string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->thingTypeName = $input['thingTypeName'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   thingTypeName?: string,
     *   '@region'?: string|null,
     * }|DeleteThingTypeRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getThingTypeName(): ?string
    {
        return $this->thingTypeName;
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
        $body = '';

        // Return the Request
        return new Request('DELETE', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setThingTypeName(?string $value): self
    {
        $this->thingTypeName = $value;

        return $this;
    }
}
