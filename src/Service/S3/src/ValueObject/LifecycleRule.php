<?php

namespace AsyncAws\S3\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\S3\Enum\ExpirationStatus;

/**
 * A lifecycle rule for individual objects in an Amazon S3 bucket.
 *
 * For more information see, Managing your storage lifecycle [^1] in the *Amazon S3 User Guide*.
 *
 * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/object-lifecycle-mgmt.html
 */
final class LifecycleRule
{
    /**
     * Specifies the expiration for the lifecycle of the object in the form of date, days and, whether the object has a
     * delete marker.
     *
     * @var LifecycleExpiration|null
     */
    private $expiration;

    /**
     * Unique identifier for the rule. The value cannot be longer than 255 characters.
     *
     * @var string|null
     */
    private $id;

    /**
     * The general purpose bucket prefix that identifies one or more objects to which the rule applies. We recommend using
     * `Filter` instead of `Prefix` for new PUTs. Previous configurations where a prefix is defined will continue to operate
     * as before.
     *
     * ! Replacement must be made for object keys containing special characters (such as carriage returns) when using XML
     * ! requests. For more information, see XML related object key constraints [^1].
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/object-keys.html#object-key-xml-related-constraints
     *
     * @var string|null
     */
    private $prefix;

    /**
     * The `Filter` is used to identify objects that a Lifecycle Rule applies to. A `Filter` must have exactly one of
     * `Prefix`, `Tag`, `ObjectSizeGreaterThan`, `ObjectSizeLessThan`, or `And` specified. `Filter` is required if the
     * `LifecycleRule` does not contain a `Prefix` element.
     *
     * For more information about `Tag` filters, see Adding filters to Lifecycle rules [^1] in the *Amazon S3 User Guide*.
     *
     * > `Tag` filters are not supported for directory buckets.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/intro-lifecycle-filters.html
     *
     * @var LifecycleRuleFilter|null
     */
    private $filter;

    /**
     * If 'Enabled', the rule is currently being applied. If 'Disabled', the rule is not currently being applied.
     *
     * @var ExpirationStatus::*
     */
    private $status;

    /**
     * Specifies when an Amazon S3 object transitions to a specified storage class.
     *
     * > This parameter applies to general purpose buckets only. It is not supported for directory bucket lifecycle
     * > configurations.
     *
     * @var Transition[]|null
     */
    private $transitions;

    /**
     * Specifies the transition rule for the lifecycle rule that describes when noncurrent objects transition to a specific
     * storage class. If your bucket is versioning-enabled (or versioning is suspended), you can set this action to request
     * that Amazon S3 transition noncurrent object versions to a specific storage class at a set period in the object's
     * lifetime.
     *
     * > This parameter applies to general purpose buckets only. It is not supported for directory bucket lifecycle
     * > configurations.
     *
     * @var NoncurrentVersionTransition[]|null
     */
    private $noncurrentVersionTransitions;

    /**
     * @var NoncurrentVersionExpiration|null
     */
    private $noncurrentVersionExpiration;

    /**
     * @var AbortIncompleteMultipartUpload|null
     */
    private $abortIncompleteMultipartUpload;

    /**
     * @param array{
     *   Expiration?: LifecycleExpiration|array|null,
     *   ID?: string|null,
     *   Prefix?: string|null,
     *   Filter?: LifecycleRuleFilter|array|null,
     *   Status: ExpirationStatus::*,
     *   Transitions?: array<Transition|array>|null,
     *   NoncurrentVersionTransitions?: array<NoncurrentVersionTransition|array>|null,
     *   NoncurrentVersionExpiration?: NoncurrentVersionExpiration|array|null,
     *   AbortIncompleteMultipartUpload?: AbortIncompleteMultipartUpload|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->expiration = isset($input['Expiration']) ? LifecycleExpiration::create($input['Expiration']) : null;
        $this->id = $input['ID'] ?? null;
        $this->prefix = $input['Prefix'] ?? null;
        $this->filter = isset($input['Filter']) ? LifecycleRuleFilter::create($input['Filter']) : null;
        $this->status = $input['Status'] ?? $this->throwException(new InvalidArgument('Missing required field "Status".'));
        $this->transitions = isset($input['Transitions']) ? array_map([Transition::class, 'create'], $input['Transitions']) : null;
        $this->noncurrentVersionTransitions = isset($input['NoncurrentVersionTransitions']) ? array_map([NoncurrentVersionTransition::class, 'create'], $input['NoncurrentVersionTransitions']) : null;
        $this->noncurrentVersionExpiration = isset($input['NoncurrentVersionExpiration']) ? NoncurrentVersionExpiration::create($input['NoncurrentVersionExpiration']) : null;
        $this->abortIncompleteMultipartUpload = isset($input['AbortIncompleteMultipartUpload']) ? AbortIncompleteMultipartUpload::create($input['AbortIncompleteMultipartUpload']) : null;
    }

    /**
     * @param array{
     *   Expiration?: LifecycleExpiration|array|null,
     *   ID?: string|null,
     *   Prefix?: string|null,
     *   Filter?: LifecycleRuleFilter|array|null,
     *   Status: ExpirationStatus::*,
     *   Transitions?: array<Transition|array>|null,
     *   NoncurrentVersionTransitions?: array<NoncurrentVersionTransition|array>|null,
     *   NoncurrentVersionExpiration?: NoncurrentVersionExpiration|array|null,
     *   AbortIncompleteMultipartUpload?: AbortIncompleteMultipartUpload|array|null,
     * }|LifecycleRule $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAbortIncompleteMultipartUpload(): ?AbortIncompleteMultipartUpload
    {
        return $this->abortIncompleteMultipartUpload;
    }

    public function getExpiration(): ?LifecycleExpiration
    {
        return $this->expiration;
    }

    public function getFilter(): ?LifecycleRuleFilter
    {
        return $this->filter;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getNoncurrentVersionExpiration(): ?NoncurrentVersionExpiration
    {
        return $this->noncurrentVersionExpiration;
    }

    /**
     * @return NoncurrentVersionTransition[]
     */
    public function getNoncurrentVersionTransitions(): array
    {
        return $this->noncurrentVersionTransitions ?? [];
    }

    /**
     * @deprecated
     */
    public function getPrefix(): ?string
    {
        @trigger_error(\sprintf('The property "Prefix" of "%s" is deprecated by AWS.', __CLASS__), \E_USER_DEPRECATED);

        return $this->prefix;
    }

    /**
     * @return ExpirationStatus::*
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return Transition[]
     */
    public function getTransitions(): array
    {
        return $this->transitions ?? [];
    }

    /**
     * @internal
     */
    public function requestBody(\DOMElement $node, \DOMDocument $document): void
    {
        if (null !== $v = $this->expiration) {
            $node->appendChild($child = $document->createElement('Expiration'));

            $v->requestBody($child, $document);
        }
        if (null !== $v = $this->id) {
            $node->appendChild($document->createElement('ID', $v));
        }
        if (null !== $v = $this->prefix) {
            @trigger_error(\sprintf('The property "Prefix" of "%s" is deprecated by AWS.', __CLASS__), \E_USER_DEPRECATED);
            $node->appendChild($document->createElement('Prefix', $v));
        }
        if (null !== $v = $this->filter) {
            $node->appendChild($child = $document->createElement('Filter'));

            $v->requestBody($child, $document);
        }
        $v = $this->status;
        if (!ExpirationStatus::exists($v)) {
            /** @psalm-suppress NoValue */
            throw new InvalidArgument(\sprintf('Invalid parameter "Status" for "%s". The value "%s" is not a valid "ExpirationStatus".', __CLASS__, $v));
        }
        $node->appendChild($document->createElement('Status', $v));
        if (null !== $v = $this->transitions) {
            foreach ($v as $item) {
                $node->appendChild($child = $document->createElement('Transition'));

                $item->requestBody($child, $document);
            }
        }
        if (null !== $v = $this->noncurrentVersionTransitions) {
            foreach ($v as $item) {
                $node->appendChild($child = $document->createElement('NoncurrentVersionTransition'));

                $item->requestBody($child, $document);
            }
        }
        if (null !== $v = $this->noncurrentVersionExpiration) {
            $node->appendChild($child = $document->createElement('NoncurrentVersionExpiration'));

            $v->requestBody($child, $document);
        }
        if (null !== $v = $this->abortIncompleteMultipartUpload) {
            $node->appendChild($child = $document->createElement('AbortIncompleteMultipartUpload'));

            $v->requestBody($child, $document);
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
