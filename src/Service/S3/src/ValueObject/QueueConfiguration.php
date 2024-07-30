<?php

namespace AsyncAws\S3\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\S3\Enum\Event;

/**
 * Specifies the configuration for publishing messages to an Amazon Simple Queue Service (Amazon SQS) queue when Amazon
 * S3 detects specified events.
 */
final class QueueConfiguration
{
    /**
     * @var string|null
     */
    private $id;

    /**
     * The Amazon Resource Name (ARN) of the Amazon SQS queue to which Amazon S3 publishes a message when it detects events
     * of the specified type.
     *
     * @var string
     */
    private $queueArn;

    /**
     * A collection of bucket events for which to send notifications.
     *
     * @var list<Event::*>
     */
    private $events;

    /**
     * @var NotificationConfigurationFilter|null
     */
    private $filter;

    /**
     * @param array{
     *   Id?: null|string,
     *   QueueArn: string,
     *   Events: array<Event::*>,
     *   Filter?: null|NotificationConfigurationFilter|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->id = $input['Id'] ?? null;
        $this->queueArn = $input['QueueArn'] ?? $this->throwException(new InvalidArgument('Missing required field "QueueArn".'));
        $this->events = $input['Events'] ?? $this->throwException(new InvalidArgument('Missing required field "Events".'));
        $this->filter = isset($input['Filter']) ? NotificationConfigurationFilter::create($input['Filter']) : null;
    }

    /**
     * @param array{
     *   Id?: null|string,
     *   QueueArn: string,
     *   Events: array<Event::*>,
     *   Filter?: null|NotificationConfigurationFilter|array,
     * }|QueueConfiguration $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return list<Event::*>
     */
    public function getEvents(): array
    {
        return $this->events;
    }

    public function getFilter(): ?NotificationConfigurationFilter
    {
        return $this->filter;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getQueueArn(): string
    {
        return $this->queueArn;
    }

    /**
     * @internal
     */
    public function requestBody(\DOMElement $node, \DOMDocument $document): void
    {
        if (null !== $v = $this->id) {
            $node->appendChild($document->createElement('Id', $v));
        }
        $v = $this->queueArn;
        $node->appendChild($document->createElement('Queue', $v));
        $v = $this->events;
        foreach ($v as $item) {
            if (!Event::exists($item)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "Event" for "%s". The value "%s" is not a valid "Event".', __CLASS__, $item));
            }
            $node->appendChild($document->createElement('Event', $item));
        }

        if (null !== $v = $this->filter) {
            $node->appendChild($child = $document->createElement('Filter'));

            $v->requestBody($child, $document);
        }
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
