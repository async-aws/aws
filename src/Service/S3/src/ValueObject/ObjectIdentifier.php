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
     * Version ID for the specific version of the object to delete.
     *
     * > This functionality is not supported for directory buckets.
     *
     * @var string|null
     */
    private $versionId;

    /**
     * An entity tag (ETag) is an identifier assigned by a web server to a specific version of a resource found at a URL.
     * This header field makes the request method conditional on `ETags`.
     *
     * > Entity tags (ETags) for S3 Express One Zone are random alphanumeric strings unique to the object.
     *
     * @var string|null
     */
    private $etag;

    /**
     * If present, the objects are deleted only if its modification times matches the provided `Timestamp`.
     *
     * > This functionality is only supported for directory buckets.
     *
     * @var \DateTimeImmutable|null
     */
    private $lastModifiedTime;

    /**
     * If present, the objects are deleted only if its size matches the provided size in bytes.
     *
     * > This functionality is only supported for directory buckets.
     *
     * @var int|null
     */
    private $size;

    /**
     * @param array{
     *   Key: string,
     *   VersionId?: null|string,
     *   ETag?: null|string,
     *   LastModifiedTime?: null|\DateTimeImmutable,
     *   Size?: null|int,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->key = $input['Key'] ?? $this->throwException(new InvalidArgument('Missing required field "Key".'));
        $this->versionId = $input['VersionId'] ?? null;
        $this->etag = $input['ETag'] ?? null;
        $this->lastModifiedTime = $input['LastModifiedTime'] ?? null;
        $this->size = $input['Size'] ?? null;
    }

    /**
     * @param array{
     *   Key: string,
     *   VersionId?: null|string,
     *   ETag?: null|string,
     *   LastModifiedTime?: null|\DateTimeImmutable,
     *   Size?: null|int,
     * }|ObjectIdentifier $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getEtag(): ?string
    {
        return $this->etag;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getLastModifiedTime(): ?\DateTimeImmutable
    {
        return $this->lastModifiedTime;
    }

    public function getSize(): ?int
    {
        return $this->size;
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
        if (null !== $v = $this->etag) {
            $node->appendChild($document->createElement('ETag', $v));
        }
        if (null !== $v = $this->lastModifiedTime) {
            $node->appendChild($document->createElement('LastModifiedTime', $v->format(\DateTimeInterface::RFC822)));
        }
        if (null !== $v = $this->size) {
            $node->appendChild($document->createElement('Size', (string) $v));
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
