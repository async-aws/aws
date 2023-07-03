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
     * different record types, see Supported DNS Resource Record Types [^1] in the *Amazon Route 53 Developer Guide*.
     *
     * You can specify more than one value for all record types except `CNAME` and `SOA`.
     *
     * > If you're creating an alias resource record set, omit `Value`.
     *
     * [^1]: https://docs.aws.amazon.com/Route53/latest/DeveloperGuide/ResourceRecordTypes.html
     *
     * @var string
     */
    private $value;

    /**
     * @param array{
     *   Value: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->value = $input['Value'] ?? $this->throwException(new InvalidArgument('Missing required field "Value".'));
    }

    /**
     * @param array{
     *   Value: string,
     * }|ResourceRecord $input
     */
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
    public function requestBody(\DOMElement $node, \DOMDocument $document): void
    {
        $v = $this->value;
        $node->appendChild($document->createElement('Value', $v));
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
