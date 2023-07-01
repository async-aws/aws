<?php

namespace AsyncAws\AppSync\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Describes an Amazon EventBridge bus data source configuration.
 */
final class EventBridgeDataSourceConfig
{
    /**
     * The ARN of the event bus. For more information about event buses, see Amazon EventBridge event buses [^1].
     *
     * [^1]: https://docs.aws.amazon.com/eventbridge/latest/userguide/eb-event-bus.html
     */
    private $eventBusArn;

    /**
     * @param array{
     *   eventBusArn: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->eventBusArn = $input['eventBusArn'] ?? $this->throwException(new InvalidArgument('Missing required field "eventBusArn".'));
    }

    /**
     * @param array{
     *   eventBusArn: string,
     * }|EventBridgeDataSourceConfig $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getEventBusArn(): string
    {
        return $this->eventBusArn;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->eventBusArn) {
            throw new InvalidArgument(sprintf('Missing parameter "eventBusArn" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['eventBusArn'] = $v;

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
