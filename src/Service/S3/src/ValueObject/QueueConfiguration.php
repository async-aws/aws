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
    private $Id;

    /**
     * The Amazon Resource Name (ARN) of the Amazon SQS queue to which Amazon S3 publishes a message when it detects events
     * of the specified type.
     */
    private $QueueArn;

    /**
     * A collection of bucket events for which to send notifications.
     */
    private $Events;

    private $Filter;

    /**
     * @param array{
     *   Id?: null|string,
     *   QueueArn: string,
     *   Events: list<Event::*>,
     *   Filter?: null|NotificationConfigurationFilter|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Id = $input['Id'] ?? null;
        $this->QueueArn = $input['QueueArn'] ?? null;
        $this->Events = $input['Events'] ?? null;
        $this->Filter = isset($input['Filter']) ? NotificationConfigurationFilter::create($input['Filter']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return list<Event::*>
     */
    public function getEvents(): array
    {
        return $this->Events ?? [];
    }

    public function getFilter(): ?NotificationConfigurationFilter
    {
        return $this->Filter;
    }

    public function getId(): ?string
    {
        return $this->Id;
    }

    public function getQueueArn(): string
    {
        return $this->QueueArn;
    }

    /**
     * @internal
     */
    public function requestBody(\DomElement $node, \DomDocument $document): void
    {
        if (null !== $v = $this->Id) {
            $node->appendChild($document->createElement('Id', $v));
        }
        if (null === $v = $this->QueueArn) {
            throw new InvalidArgument(sprintf('Missing parameter "QueueArn" for "%s". The value cannot be null.', __CLASS__));
        }
        $node->appendChild($document->createElement('Queue', $v));
        if (null === $v = $this->Events) {
            throw new InvalidArgument(sprintf('Missing parameter "Events" for "%s". The value cannot be null.', __CLASS__));
        }
        foreach ($v as $item) {
            if (!Event::exists($item)) {
                throw new InvalidArgument(sprintf('Invalid parameter "Event" for "%s". The value "%s" is not a valid "Event".', __CLASS__, $item));
            }
            $node->appendChild($document->createElement('Event', $item));
        }

        if (null !== $v = $this->Filter) {
            $node->appendChild($child = $document->createElement('Filter'));

            $v->requestBody($child, $document);
        }
    }
}
