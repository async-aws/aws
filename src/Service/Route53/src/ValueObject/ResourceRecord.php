<?php

namespace AsyncAws\Route53\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Information specific to the resource record.
 *
 * > If you're creating an alias resource record set, omit `ResourceRecord`.
 */
final class ResourceRecord
{
    /**
     * The current or new DNS record value, not to exceed 4,000 characters. In the case of a `DELETE` action, if the current
     * value does not match the actual value, an error is returned. For descriptions about how to format `Value` for
     * different record types, see Supported DNS Resource Record Types in the *Amazon Route 53 Developer Guide*.
     *
     * @see https://docs.aws.amazon.com/Route53/latest/DeveloperGuide/ResourceRecordTypes.html
     */
    private $value;

    /**
     * @param array{
     *   Value: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->value = $input['Value'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @internal
     */
    public function requestBody(\DomElement $node, \DomDocument $document): void
    {
        if (null === $v = $this->value) {
            throw new InvalidArgument(sprintf('Missing parameter "Value" for "%s". The value cannot be null.', __CLASS__));
        }
        $node->appendChild($document->createElement('Value', $v));
    }
}
