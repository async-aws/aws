<?php

namespace AsyncAws\Scheduler\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * The templated target type for the EventBridge `PutEvents` [^1] API operation.
 *
 * [^1]: https://docs.aws.amazon.com/eventbridge/latest/APIReference/API_PutEvents.html
 */
final class EventBridgeParameters
{
    /**
     * A free-form string, with a maximum of 128 characters, used to decide what fields to expect in the event detail.
     */
    private $detailType;

    /**
     * The source of the event.
     */
    private $source;

    /**
     * @param array{
     *   DetailType: string,
     *   Source: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->detailType = $input['DetailType'] ?? null;
        $this->source = $input['Source'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDetailType(): string
    {
        return $this->detailType;
    }

    public function getSource(): string
    {
        return $this->source;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->detailType) {
            throw new InvalidArgument(sprintf('Missing parameter "DetailType" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['DetailType'] = $v;
        if (null === $v = $this->source) {
            throw new InvalidArgument(sprintf('Missing parameter "Source" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Source'] = $v;

        return $payload;
    }
}
