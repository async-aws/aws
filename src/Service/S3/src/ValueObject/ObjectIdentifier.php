<?php

namespace AsyncAws\S3\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

class ObjectIdentifier
{
    /**
     * Key name of the object to delete.
     */
    private $Key;

    /**
     * VersionId for the specific version of the object to delete.
     */
    private $VersionId;

    /**
     * @param array{
     *   Key: string,
     *   VersionId?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Key = $input['Key'] ?? null;
        $this->VersionId = $input['VersionId'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getKey(): string
    {
        return $this->Key;
    }

    public function getVersionId(): ?string
    {
        return $this->VersionId;
    }

    /**
     * @internal
     */
    public function requestBody(\DomElement $node, \DomDocument $document): void
    {
        $node->appendChild($document->createElement('Key', $this->Key));
        if (null !== $v = $this->VersionId) {
            $node->appendChild($document->createElement('VersionId', $v));
        }
    }

    public function validate(): void
    {
        if (null === $this->Key) {
            throw new InvalidArgument(sprintf('Missing parameter "Key" when validating the "%s". The value cannot be null.', __CLASS__));
        }
    }
}
