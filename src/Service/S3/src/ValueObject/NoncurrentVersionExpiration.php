<?php

namespace AsyncAws\S3\ValueObject;

/**
 * Specifies when noncurrent object versions expire. Upon expiration, Amazon S3 permanently deletes the noncurrent
 * object versions. You set this lifecycle configuration action on a bucket that has versioning enabled (or suspended)
 * to request that Amazon S3 delete noncurrent object versions at a specific period in the object's lifetime.
 *
 * > This parameter applies to general purpose buckets only. It is not supported for directory bucket lifecycle
 * > configurations.
 */
final class NoncurrentVersionExpiration
{
    /**
     * Specifies the number of days an object is noncurrent before Amazon S3 can perform the associated action. The value
     * must be a non-zero positive integer. For information about the noncurrent days calculations, see How Amazon S3
     * Calculates When an Object Became Noncurrent [^1] in the *Amazon S3 User Guide*.
     *
     * > This parameter applies to general purpose buckets only. It is not supported for directory bucket lifecycle
     * > configurations.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/dev/intro-lifecycle-rules.html#non-current-days-calculations
     *
     * @var int|null
     */
    private $noncurrentDays;

    /**
     * Specifies how many noncurrent versions Amazon S3 will retain. You can specify up to 100 noncurrent versions to
     * retain. Amazon S3 will permanently delete any additional noncurrent versions beyond the specified number to retain.
     * For more information about noncurrent versions, see Lifecycle configuration elements [^1] in the *Amazon S3 User
     * Guide*.
     *
     * > This parameter applies to general purpose buckets only. It is not supported for directory bucket lifecycle
     * > configurations.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/intro-lifecycle-rules.html
     *
     * @var int|null
     */
    private $newerNoncurrentVersions;

    /**
     * @param array{
     *   NoncurrentDays?: int|null,
     *   NewerNoncurrentVersions?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->noncurrentDays = $input['NoncurrentDays'] ?? null;
        $this->newerNoncurrentVersions = $input['NewerNoncurrentVersions'] ?? null;
    }

    /**
     * @param array{
     *   NoncurrentDays?: int|null,
     *   NewerNoncurrentVersions?: int|null,
     * }|NoncurrentVersionExpiration $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getNewerNoncurrentVersions(): ?int
    {
        return $this->newerNoncurrentVersions;
    }

    public function getNoncurrentDays(): ?int
    {
        return $this->noncurrentDays;
    }

    /**
     * @internal
     */
    public function requestBody(\DOMElement $node, \DOMDocument $document): void
    {
        if (null !== $v = $this->noncurrentDays) {
            $node->appendChild($document->createElement('NoncurrentDays', (string) $v));
        }
        if (null !== $v = $this->newerNoncurrentVersions) {
            $node->appendChild($document->createElement('NewerNoncurrentVersions', (string) $v));
        }
    }
}
