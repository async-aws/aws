<?php

namespace AsyncAws\S3\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\S3\Enum\BucketVersioningStatus;
use AsyncAws\S3\Enum\MFADelete;

/**
 * Describes the versioning state of an Amazon S3 bucket. For more information, see PUT Bucket versioning [^1] in the
 * *Amazon S3 API Reference*.
 *
 * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/API/RESTBucketPUTVersioningStatus.html
 */
final class VersioningConfiguration
{
    /**
     * Specifies whether MFA delete is enabled in the bucket versioning configuration. This element is only returned if the
     * bucket has been configured with MFA delete. If the bucket has never been so configured, this element is not returned.
     *
     * @var MFADelete::*|null
     */
    private $mfaDelete;

    /**
     * The versioning state of the bucket.
     *
     * @var BucketVersioningStatus::*|null
     */
    private $status;

    /**
     * @param array{
     *   MFADelete?: MFADelete::*|null,
     *   Status?: BucketVersioningStatus::*|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->mfaDelete = $input['MFADelete'] ?? null;
        $this->status = $input['Status'] ?? null;
    }

    /**
     * @param array{
     *   MFADelete?: MFADelete::*|null,
     *   Status?: BucketVersioningStatus::*|null,
     * }|VersioningConfiguration $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return MFADelete::*|null
     */
    public function getMfaDelete(): ?string
    {
        return $this->mfaDelete;
    }

    /**
     * @return BucketVersioningStatus::*|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @internal
     */
    public function requestBody(\DOMElement $node, \DOMDocument $document): void
    {
        if (null !== $v = $this->mfaDelete) {
            if (!MFADelete::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "MfaDelete" for "%s". The value "%s" is not a valid "MFADelete".', __CLASS__, $v));
            }
            $node->appendChild($document->createElement('MfaDelete', $v));
        }
        if (null !== $v = $this->status) {
            if (!BucketVersioningStatus::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "Status" for "%s". The value "%s" is not a valid "BucketVersioningStatus".', __CLASS__, $v));
            }
            $node->appendChild($document->createElement('Status', $v));
        }
    }
}
