<?php

namespace AsyncAws\Iot\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Iot\ValueObject\AttributePayload;

/**
 * The input for the CreateThing operation.
 */
final class CreateThingRequest extends Input
{
    /**
     * The name of the thing to create.
     *
     * @required
     *
     * @var string|null
     */
    private $thingName;

    /**
     * The name of the thing type associated with the new thing.
     *
     * @var string|null
     */
    private $thingTypeName;

    /**
     * The attribute payload, which consists of up to three name/value pairs in a JSON document. For example:.
     *
     * @var AttributePayload|null
     */
    private $attributePayload;

    /**
     * The name of the billing group the thing will be added to.
     *
     * @var string|null
     */
    private $billingGroupName;

    /**
     * @param array{
     *   thingName?: string,
     *   thingTypeName?: string,
     *   attributePayload?: AttributePayload|array,
     *   billingGroupName?: string,
     *
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->thingName = $input['thingName'] ?? null;
        $this->thingTypeName = $input['thingTypeName'] ?? null;
        $this->attributePayload = isset($input['attributePayload']) ? AttributePayload::create($input['attributePayload']) : null;
        $this->billingGroupName = $input['billingGroupName'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAttributePayload(): ?AttributePayload
    {
        return $this->attributePayload;
    }

    public function getBillingGroupName(): ?string
    {
        return $this->billingGroupName;
    }

    public function getThingName(): ?string
    {
        return $this->thingName;
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
        if (null === $v = $this->thingName) {
            throw new InvalidArgument(sprintf('Missing parameter "thingName" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['thingName'] = $v;
        $uriString = '/things/' . rawurlencode($uri['thingName']);

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setAttributePayload(?AttributePayload $value): self
    {
        $this->attributePayload = $value;

        return $this;
    }

    public function setBillingGroupName(?string $value): self
    {
        $this->billingGroupName = $value;

        return $this;
    }

    public function setThingName(?string $value): self
    {
        $this->thingName = $value;

        return $this;
    }

    public function setThingTypeName(?string $value): self
    {
        $this->thingTypeName = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];

        if (null !== $v = $this->thingTypeName) {
            $payload['thingTypeName'] = $v;
        }
        if (null !== $v = $this->attributePayload) {
            $payload['attributePayload'] = $v->requestBody();
        }
        if (null !== $v = $this->billingGroupName) {
            $payload['billingGroupName'] = $v;
        }

        return $payload;
    }
}
