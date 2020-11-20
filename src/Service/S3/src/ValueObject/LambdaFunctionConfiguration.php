<?php

namespace AsyncAws\S3\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\S3\Enum\Event;

final class LambdaFunctionConfiguration
{
    private $Id;

    /**
     * The Amazon Resource Name (ARN) of the AWS Lambda function that Amazon S3 invokes when the specified event type
     * occurs.
     */
    private $LambdaFunctionArn;

    /**
     * The Amazon S3 bucket event for which to invoke the AWS Lambda function. For more information, see Supported Event
     * Types in the *Amazon Simple Storage Service Developer Guide*.
     *
     * @see https://docs.aws.amazon.com/AmazonS3/latest/dev/NotificationHowTo.html
     */
    private $Events;

    private $Filter;

    /**
     * @param array{
     *   Id?: null|string,
     *   LambdaFunctionArn: string,
     *   Events: list<Event::*>,
     *   Filter?: null|NotificationConfigurationFilter|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Id = $input['Id'] ?? null;
        $this->LambdaFunctionArn = $input['LambdaFunctionArn'] ?? null;
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

    public function getLambdaFunctionArn(): string
    {
        return $this->LambdaFunctionArn;
    }

    /**
     * @internal
     */
    public function requestBody(\DomElement $node, \DomDocument $document): void
    {
        if (null !== $v = $this->Id) {
            $node->appendChild($document->createElement('Id', $v));
        }
        if (null === $v = $this->LambdaFunctionArn) {
            throw new InvalidArgument(sprintf('Missing parameter "LambdaFunctionArn" for "%s". The value cannot be null.', __CLASS__));
        }
        $node->appendChild($document->createElement('CloudFunction', $v));
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
