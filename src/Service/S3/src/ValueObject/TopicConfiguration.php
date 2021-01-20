<?php

namespace AsyncAws\S3\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\S3\Enum\Event;

/**
 * A container for specifying the configuration for publication of messages to an Amazon Simple Notification Service
 * (Amazon SNS) topic when Amazon S3 detects specified events.
 */
final class TopicConfiguration
{
    private $Id;

    /**
     * The Amazon Resource Name (ARN) of the Amazon SNS topic to which Amazon S3 publishes a message when it detects events
     * of the specified type.
     */
    private $TopicArn;

    /**
     * The Amazon S3 bucket event about which to send notifications. For more information, see Supported Event Types in the
     * *Amazon Simple Storage Service Developer Guide*.
     *
     * @see https://docs.aws.amazon.com/AmazonS3/latest/dev/NotificationHowTo.html
     */
    private $Events;

    private $Filter;

    /**
     * @param array{
     *   Id?: null|string,
     *   TopicArn: string,
     *   Events: list<Event::*>,
     *   Filter?: null|NotificationConfigurationFilter|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Id = $input['Id'] ?? null;
        $this->TopicArn = $input['TopicArn'] ?? null;
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

    public function getTopicArn(): string
    {
        return $this->TopicArn;
    }

    /**
     * @internal
     */
    public function requestBody(\DomElement $node, \DomDocument $document): void
    {
        if (null !== $v = $this->Id) {
            $node->appendChild($document->createElement('Id', $v));
        }
        if (null === $v = $this->TopicArn) {
            throw new InvalidArgument(sprintf('Missing parameter "TopicArn" for "%s". The value cannot be null.', __CLASS__));
        }
        $node->appendChild($document->createElement('Topic', $v));
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
