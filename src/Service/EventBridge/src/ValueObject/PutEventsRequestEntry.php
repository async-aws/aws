<?php

namespace AsyncAws\EventBridge\ValueObject;

/**
 * Represents an event to be submitted.
 */
final class PutEventsRequestEntry
{
    /**
     * The time stamp of the event, per RFC3339 [^1]. If no time stamp is provided, the time stamp of the PutEvents [^2]
     * call is used.
     *
     * [^1]: https://www.rfc-editor.org/rfc/rfc3339.txt
     * [^2]: https://docs.aws.amazon.com/eventbridge/latest/APIReference/API_PutEvents.html
     *
     * @var \DateTimeImmutable|null
     */
    private $time;

    /**
     * The source of the event.
     *
     * > `Detail`, `DetailType`, and `Source` are required for EventBridge to successfully send an event to an event bus. If
     * > you include event entries in a request that do not include each of those properties, EventBridge fails that entry.
     * > If you submit a request in which *none* of the entries have each of these properties, EventBridge fails the entire
     * > request.
     *
     * @var string|null
     */
    private $source;

    /**
     * Amazon Web Services resources, identified by Amazon Resource Name (ARN), which the event primarily concerns. Any
     * number, including zero, may be present.
     *
     * @var string[]|null
     */
    private $resources;

    /**
     * Free-form string, with a maximum of 128 characters, used to decide what fields to expect in the event detail.
     *
     * > `Detail`, `DetailType`, and `Source` are required for EventBridge to successfully send an event to an event bus. If
     * > you include event entries in a request that do not include each of those properties, EventBridge fails that entry.
     * > If you submit a request in which *none* of the entries have each of these properties, EventBridge fails the entire
     * > request.
     *
     * @var string|null
     */
    private $detailType;

    /**
     * A valid JSON object. There is no other schema imposed. The JSON object may contain fields and nested sub-objects.
     *
     * > `Detail`, `DetailType`, and `Source` are required for EventBridge to successfully send an event to an event bus. If
     * > you include event entries in a request that do not include each of those properties, EventBridge fails that entry.
     * > If you submit a request in which *none* of the entries have each of these properties, EventBridge fails the entire
     * > request.
     *
     * @var string|null
     */
    private $detail;

    /**
     * The name or ARN of the event bus to receive the event. Only the rules that are associated with this event bus are
     * used to match the event. If you omit this, the default event bus is used.
     *
     * > If you're using a global endpoint with a custom bus, you can enter either the name or Amazon Resource Name (ARN) of
     * > the event bus in either the primary or secondary Region here. EventBridge then determines the corresponding event
     * > bus in the other Region based on the endpoint referenced by the `EndpointId`. Specifying the event bus ARN is
     * > preferred.
     *
     * @var string|null
     */
    private $eventBusName;

    /**
     * An X-Ray trace header, which is an http header (X-Amzn-Trace-Id) that contains the trace-id associated with the
     * event.
     *
     * To learn more about X-Ray trace headers, see Tracing header [^1] in the X-Ray Developer Guide.
     *
     * [^1]: https://docs.aws.amazon.com/xray/latest/devguide/xray-concepts.html#xray-concepts-tracingheader
     *
     * @var string|null
     */
    private $traceHeader;

    /**
     * @param array{
     *   Time?: \DateTimeImmutable|null,
     *   Source?: string|null,
     *   Resources?: string[]|null,
     *   DetailType?: string|null,
     *   Detail?: string|null,
     *   EventBusName?: string|null,
     *   TraceHeader?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->time = $input['Time'] ?? null;
        $this->source = $input['Source'] ?? null;
        $this->resources = $input['Resources'] ?? null;
        $this->detailType = $input['DetailType'] ?? null;
        $this->detail = $input['Detail'] ?? null;
        $this->eventBusName = $input['EventBusName'] ?? null;
        $this->traceHeader = $input['TraceHeader'] ?? null;
    }

    /**
     * @param array{
     *   Time?: \DateTimeImmutable|null,
     *   Source?: string|null,
     *   Resources?: string[]|null,
     *   DetailType?: string|null,
     *   Detail?: string|null,
     *   EventBusName?: string|null,
     *   TraceHeader?: string|null,
     * }|PutEventsRequestEntry $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDetail(): ?string
    {
        return $this->detail;
    }

    public function getDetailType(): ?string
    {
        return $this->detailType;
    }

    public function getEventBusName(): ?string
    {
        return $this->eventBusName;
    }

    /**
     * @return string[]
     */
    public function getResources(): array
    {
        return $this->resources ?? [];
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function getTime(): ?\DateTimeImmutable
    {
        return $this->time;
    }

    public function getTraceHeader(): ?string
    {
        return $this->traceHeader;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->time) {
            $payload['Time'] = $v->getTimestamp();
        }
        if (null !== $v = $this->source) {
            $payload['Source'] = $v;
        }
        if (null !== $v = $this->resources) {
            $index = -1;
            $payload['Resources'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['Resources'][$index] = $listValue;
            }
        }
        if (null !== $v = $this->detailType) {
            $payload['DetailType'] = $v;
        }
        if (null !== $v = $this->detail) {
            $payload['Detail'] = $v;
        }
        if (null !== $v = $this->eventBusName) {
            $payload['EventBusName'] = $v;
        }
        if (null !== $v = $this->traceHeader) {
            $payload['TraceHeader'] = $v;
        }

        return $payload;
    }
}
