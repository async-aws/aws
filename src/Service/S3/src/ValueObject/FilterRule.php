<?php

namespace AsyncAws\S3\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\S3\Enum\FilterRuleName;

final class FilterRule
{
    /**
     * The object key name prefix or suffix identifying one or more objects to which the filtering rule applies. The maximum
     * length is 1,024 characters. Overlapping prefixes and suffixes are not supported. For more information, see
     * Configuring Event Notifications in the *Amazon Simple Storage Service Developer Guide*.
     *
     * @see https://docs.aws.amazon.com/AmazonS3/latest/dev/NotificationHowTo.html
     */
    private $Name;

    /**
     * The value that the filter searches for in object key names.
     */
    private $Value;

    /**
     * @param array{
     *   Name?: null|FilterRuleName::*,
     *   Value?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Name = $input['Name'] ?? null;
        $this->Value = $input['Value'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return FilterRuleName::*|null
     */
    public function getName(): ?string
    {
        return $this->Name;
    }

    public function getValue(): ?string
    {
        return $this->Value;
    }

    /**
     * @internal
     */
    public function requestBody(\DomElement $node, \DomDocument $document): void
    {
        if (null !== $v = $this->Name) {
            if (!FilterRuleName::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "Name" for "%s". The value "%s" is not a valid "FilterRuleName".', __CLASS__, $v));
            }
            $node->appendChild($document->createElement('Name', $v));
        }
        if (null !== $v = $this->Value) {
            $node->appendChild($document->createElement('Value', $v));
        }
    }
}
