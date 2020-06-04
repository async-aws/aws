<?php

namespace AsyncAws\EventBridge\ValueObject;

final class PutEventsRequestEntry
{
    /**
     * The time stamp of the event, per RFC3339. If no time stamp is provided, the time stamp of the PutEvents call is used.
     *
     * @see https://www.rfc-editor.org/rfc/rfc3339.txt
     */
    private $Time;

    /**
     * The source of the event.
     */
    private $Source;

    /**
     * AWS resources, identified by Amazon Resource Name (ARN), which the event primarily concerns. Any number, including
     * zero, may be present.
     */
    private $Resources;

    /**
     * Free-form string used to decide what fields to expect in the event detail.
     */
    private $DetailType;

    /**
     * A valid JSON string. There is no other schema imposed. The JSON string may contain fields and nested subobjects.
     */
    private $Detail;

    /**
     * The event bus that will receive the event. Only the rules that are associated with this event bus will be able to
     * match the event.
     */
    private $EventBusName;

    /**
     * @param array{
     *   Time?: null|\DateTimeImmutable,
     *   Source?: null|string,
     *   Resources?: null|string[],
     *   DetailType?: null|string,
     *   Detail?: null|string,
     *   EventBusName?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Time = $input['Time'] ?? null;
        $this->Source = $input['Source'] ?? null;
        $this->Resources = $input['Resources'] ?? null;
        $this->DetailType = $input['DetailType'] ?? null;
        $this->Detail = $input['Detail'] ?? null;
        $this->EventBusName = $input['EventBusName'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDetail(): ?string
    {
        return $this->Detail;
    }

    public function getDetailType(): ?string
    {
        return $this->DetailType;
    }

    public function getEventBusName(): ?string
    {
        return $this->EventBusName;
    }

    /**
     * @return string[]
     */
    public function getResources(): array
    {
        return $this->Resources ?? [];
    }

    public function getSource(): ?string
    {
        return $this->Source;
    }

    public function getTime(): ?\DateTimeImmutable
    {
        return $this->Time;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->Time) {
            $payload['Time'] = $v->format(\DateTimeInterface::ATOM);
        }
        if (null !== $v = $this->Source) {
            $payload['Source'] = $v;
        }
        if (null !== $v = $this->Resources) {
            $index = -1;
            $payload['Resources'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['Resources'][$index] = $listValue;
            }
        }
        if (null !== $v = $this->DetailType) {
            $payload['DetailType'] = $v;
        }
        if (null !== $v = $this->Detail) {
            $payload['Detail'] = $v;
        }
        if (null !== $v = $this->EventBusName) {
            $payload['EventBusName'] = $v;
        }

        return $payload;
    }
}
