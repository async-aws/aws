<?php

namespace AsyncAws\S3\ValueObject;

final class NotificationConfigurationFilter
{
    private $Key;

    /**
     * @param array{
     *   Key?: null|S3KeyFilter|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Key = isset($input['Key']) ? S3KeyFilter::create($input['Key']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getKey(): ?S3KeyFilter
    {
        return $this->Key;
    }

    /**
     * @internal
     */
    public function requestBody(\DomElement $node, \DomDocument $document): void
    {
        if (null !== $v = $this->Key) {
            $node->appendChild($child = $document->createElement('S3Key'));

            $v->requestBody($child, $document);
        }
    }
}
