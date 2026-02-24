<?php

namespace AsyncAws\S3\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\S3\Enum\TransitionStorageClass;

/**
 * Container for the transition rule that describes when noncurrent objects transition to the `STANDARD_IA`,
 * `ONEZONE_IA`, `INTELLIGENT_TIERING`, `GLACIER_IR`, `GLACIER`, or `DEEP_ARCHIVE` storage class. If your bucket is
 * versioning-enabled (or versioning is suspended), you can set this action to request that Amazon S3 transition
 * noncurrent object versions to the `STANDARD_IA`, `ONEZONE_IA`, `INTELLIGENT_TIERING`, `GLACIER_IR`, `GLACIER`, or
 * `DEEP_ARCHIVE` storage class at a specific period in the object's lifetime.
 */
final class NoncurrentVersionTransition
{
    /**
     * Specifies the number of days an object is noncurrent before Amazon S3 can perform the associated action. For
     * information about the noncurrent days calculations, see How Amazon S3 Calculates How Long an Object Has Been
     * Noncurrent [^1] in the *Amazon S3 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/dev/intro-lifecycle-rules.html#non-current-days-calculations
     *
     * @var int|null
     */
    private $noncurrentDays;

    /**
     * The class of storage used to store the object.
     *
     * @var TransitionStorageClass::*|null
     */
    private $storageClass;

    /**
     * Specifies how many noncurrent versions Amazon S3 will retain in the same storage class before transitioning objects.
     * You can specify up to 100 noncurrent versions to retain. Amazon S3 will transition any additional noncurrent versions
     * beyond the specified number to retain. For more information about noncurrent versions, see Lifecycle configuration
     * elements [^1] in the *Amazon S3 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/intro-lifecycle-rules.html
     *
     * @var int|null
     */
    private $newerNoncurrentVersions;

    /**
     * @param array{
     *   NoncurrentDays?: int|null,
     *   StorageClass?: TransitionStorageClass::*|null,
     *   NewerNoncurrentVersions?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->noncurrentDays = $input['NoncurrentDays'] ?? null;
        $this->storageClass = $input['StorageClass'] ?? null;
        $this->newerNoncurrentVersions = $input['NewerNoncurrentVersions'] ?? null;
    }

    /**
     * @param array{
     *   NoncurrentDays?: int|null,
     *   StorageClass?: TransitionStorageClass::*|null,
     *   NewerNoncurrentVersions?: int|null,
     * }|NoncurrentVersionTransition $input
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
     * @return TransitionStorageClass::*|null
     */
    public function getStorageClass(): ?string
    {
        return $this->storageClass;
    }

    /**
     * @internal
     */
    public function requestBody(\DOMElement $node, \DOMDocument $document): void
    {
        if (null !== $v = $this->noncurrentDays) {
            $node->appendChild($document->createElement('NoncurrentDays', (string) $v));
        }
        if (null !== $v = $this->storageClass) {
            if (!TransitionStorageClass::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "StorageClass" for "%s". The value "%s" is not a valid "TransitionStorageClass".', __CLASS__, $v));
            }
            $node->appendChild($document->createElement('StorageClass', $v));
        }
        if (null !== $v = $this->newerNoncurrentVersions) {
            $node->appendChild($document->createElement('NewerNoncurrentVersions', (string) $v));
        }
    }
}
