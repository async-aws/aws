<?php

namespace AsyncAws\S3\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Object Identifier is unique value to identify objects.
 */
final class ObjectIdentifier
{
    /**
     * Key name of the object.
     *
     * ! Replacement must be made for object keys containing special characters (such as carriage returns) when using XML
     * ! requests. For more information, see XML related object key constraints [^1].
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/object-keys.html#object-key-xml-related-constraints
     *
     * @var string
     */
    private $key;

    /**
     * VersionId for the specific version of the object to delete.
     *
     * @var string|null
     */
    private $versionId;

    /**
     * @param array{
     *   Key: string,
     *   VersionId?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->key = $input['Key'] ?? $this->throwException(new InvalidArgument('Missing required field "Key".'));
        $this->versionId = $input['VersionId'] ?? null;
    }

    /**
     * @param array{
     *   Key: string,
     *   VersionId?: null|string,
     * }|ObjectIdentifier $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getVersionId(): ?string
    {
        return $this->versionId;
    }

    /**
     * @internal
     */
    public function requestBody(\DOMElement $node, \DOMDocument $document): void
    {
        $v = $this->key;
        $node->appendChild($document->createElement('Key', $v));
        if (null !== $v = $this->versionId) {
            $node->appendChild($document->createElement('VersionId', $v));
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
