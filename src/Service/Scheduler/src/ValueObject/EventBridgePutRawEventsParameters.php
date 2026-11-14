<?php

namespace AsyncAws\Scheduler\ValueObject;

/**
 * The templated target type for the EventBridge event bus v2 `PutRawEvents` [^1] API operation.
 *
 * [^1]: https://docs.aws.amazon.com/eventbridgev2/latest/APIReference/API_PutRawEvents.html
 */
final class EventBridgePutRawEventsParameters
{
    /**
     * Optional metadata key-value pairs you define. Keys must be 1-128 characters and must not contain "/".
     *
     * @var array<string, string>|null
     */
    private $metadata;

    /**
     * @param array{
     *   Metadata?: array<string, string>|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->metadata = $input['Metadata'] ?? null;
    }

    /**
     * @param array{
     *   Metadata?: array<string, string>|null,
     * }|EventBridgePutRawEventsParameters $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return array<string, string>
     */
    public function getMetadata(): array
    {
        return $this->metadata ?? [];
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->metadata) {
            if (empty($v)) {
                $payload['Metadata'] = new \stdClass();
            } else {
                $payload['Metadata'] = [];
                foreach ($v as $name => $mv) {
                    $payload['Metadata'][$name] = $mv;
                }
            }
        }

        return $payload;
    }
}
