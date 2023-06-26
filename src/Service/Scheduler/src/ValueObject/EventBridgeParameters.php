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
     *
     * @var string
     */
    private $detailType;

    /**
     * The source of the event.
     *
     * @var string
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
        $this->detailType = $input['DetailType'] ?? $this->throwException(new InvalidArgument('Missing required field "DetailType".'));
        $this->source = $input['Source'] ?? $this->throwException(new InvalidArgument('Missing required field "Source".'));
    }

    /**
     * @param array{
     *   DetailType: string,
     *   Source: string,
     * }|EventBridgeParameters $input
     */
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
        $v = $this->detailType;
        $payload['DetailType'] = $v;
        $v = $this->source;
        $payload['Source'] = $v;

        return $payload;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
