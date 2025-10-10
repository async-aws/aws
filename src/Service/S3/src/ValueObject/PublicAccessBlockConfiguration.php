<?php

namespace AsyncAws\S3\ValueObject;

/**
 * The PublicAccessBlock configuration that you want to apply to this Amazon S3 bucket. You can enable the configuration
 * options in any combination. For more information about when Amazon S3 considers a bucket or object public, see The
 * Meaning of "Public" [^1] in the *Amazon S3 User Guide*.
 *
 * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/dev/access-control-block-public-access.html#access-control-block-public-access-policy-status
 */
final class PublicAccessBlockConfiguration
{
    /**
     * Specifies whether Amazon S3 should block public access control lists (ACLs) for this bucket and objects in this
     * bucket. Setting this element to `TRUE` causes the following behavior:
     *
     * - PUT Bucket ACL and PUT Object ACL calls fail if the specified ACL is public.
     * - PUT Object calls fail if the request includes a public ACL.
     * - PUT Bucket calls fail if the request includes a public ACL.
     *
     * Enabling this setting doesn't affect existing policies or ACLs.
     *
     * @var bool|null
     */
    private $blockPublicAcls;

    /**
     * Specifies whether Amazon S3 should ignore public ACLs for this bucket and objects in this bucket. Setting this
     * element to `TRUE` causes Amazon S3 to ignore all public ACLs on this bucket and objects in this bucket.
     *
     * Enabling this setting doesn't affect the persistence of any existing ACLs and doesn't prevent new public ACLs from
     * being set.
     *
     * @var bool|null
     */
    private $ignorePublicAcls;

    /**
     * Specifies whether Amazon S3 should block public bucket policies for this bucket. Setting this element to `TRUE`
     * causes Amazon S3 to reject calls to PUT Bucket policy if the specified bucket policy allows public access.
     *
     * Enabling this setting doesn't affect existing bucket policies.
     *
     * @var bool|null
     */
    private $blockPublicPolicy;

    /**
     * Specifies whether Amazon S3 should restrict public bucket policies for this bucket. Setting this element to `TRUE`
     * restricts access to this bucket to only Amazon Web Services service principals and authorized users within this
     * account if the bucket has a public policy.
     *
     * Enabling this setting doesn't affect previously stored bucket policies, except that public and cross-account access
     * within any public bucket policy, including non-public delegation to specific accounts, is blocked.
     *
     * @var bool|null
     */
    private $restrictPublicBuckets;

    /**
     * @param array{
     *   BlockPublicAcls?: bool|null,
     *   IgnorePublicAcls?: bool|null,
     *   BlockPublicPolicy?: bool|null,
     *   RestrictPublicBuckets?: bool|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->blockPublicAcls = $input['BlockPublicAcls'] ?? null;
        $this->ignorePublicAcls = $input['IgnorePublicAcls'] ?? null;
        $this->blockPublicPolicy = $input['BlockPublicPolicy'] ?? null;
        $this->restrictPublicBuckets = $input['RestrictPublicBuckets'] ?? null;
    }

    /**
     * @param array{
     *   BlockPublicAcls?: bool|null,
     *   IgnorePublicAcls?: bool|null,
     *   BlockPublicPolicy?: bool|null,
     *   RestrictPublicBuckets?: bool|null,
     * }|PublicAccessBlockConfiguration $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getBlockPublicAcls(): ?bool
    {
        return $this->blockPublicAcls;
    }

    public function getBlockPublicPolicy(): ?bool
    {
        return $this->blockPublicPolicy;
    }

    public function getIgnorePublicAcls(): ?bool
    {
        return $this->ignorePublicAcls;
    }

    public function getRestrictPublicBuckets(): ?bool
    {
        return $this->restrictPublicBuckets;
    }

    /**
     * @internal
     */
    public function requestBody(\DOMElement $node, \DOMDocument $document): void
    {
        if (null !== $v = $this->blockPublicAcls) {
            $node->appendChild($document->createElement('BlockPublicAcls', $v ? 'true' : 'false'));
        }
        if (null !== $v = $this->ignorePublicAcls) {
            $node->appendChild($document->createElement('IgnorePublicAcls', $v ? 'true' : 'false'));
        }
        if (null !== $v = $this->blockPublicPolicy) {
            $node->appendChild($document->createElement('BlockPublicPolicy', $v ? 'true' : 'false'));
        }
        if (null !== $v = $this->restrictPublicBuckets) {
            $node->appendChild($document->createElement('RestrictPublicBuckets', $v ? 'true' : 'false'));
        }
    }
}
