<?php

namespace AsyncAws\EventBridge\ValueObject;

/**
 * Represents an event to be submitted.
 */
final class PutEventsRequestEntry
{
    /**
     * The time stamp of the event, per RFC3339. If no time stamp is provided, the time stamp of the PutEvents call is used.
     *
     * @see https://www.rfc-editor.org/rfc/rfc3339.txt
     */
    private $time;

    /**
     * The source of the event.
     */
    private $source;

    /**
     * AWS resources, identified by Amazon Resource Name (ARN), which the event primarily concerns. Any number, including
     * zero, may be present.
     */
    private $resources;

    /**
     * Free-form string used to decide what fields to expect in the event detail.
     */
    private $detailType;

    /**
     * A valid JSON string. There is no other schema imposed. The JSON string may contain fields and nested subobjects.
     */
    private $detail;

    /**
     * The name or ARN of the event bus to receive the event. Only the rules that are associated with this event bus are
     * used to match the event. If you omit this, the default event bus is used.
     */
    private $eventBusName;

    /**
     * An AWS X-Ray trade header, which is an http header (X-Amzn-Trace-Id) that contains the trace-id associated with the
     * event.
     */
    private $traceHeader;

    /**
     * @param array{
     *   Time?: null|\DateTimeImmutable,
     *   Source?: null|string,
     *   Resources?: null|string[],
     *   DetailType?: null|string,
     *   Detail?: null|string,
     *   EventBusName?: null|string,
     *   TraceHeader?: null|string,
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
            $payload['Time'] = $v->format(\DateTimeInterface::ATOM);
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
