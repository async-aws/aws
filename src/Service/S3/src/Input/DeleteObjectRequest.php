<?php

namespace AsyncAws\S3\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\S3\Enum\RequestPayer;

final class DeleteObjectRequest extends Input
{
    /**
     * The bucket name of the bucket containing the object.
     *
     * **Directory buckets** - When you use this operation with a directory bucket, you must use virtual-hosted-style
     * requests in the format `*Bucket_name*.s3express-*az_id*.*region*.amazonaws.com`. Path-style requests are not
     * supported. Directory bucket names must be unique in the chosen Availability Zone. Bucket names must follow the format
     * `*bucket_base_name*--*az-id*--x-s3` (for example, `*DOC-EXAMPLE-BUCKET*--*usw2-az1*--x-s3`). For information about
     * bucket naming restrictions, see Directory bucket naming rules [^1] in the *Amazon S3 User Guide*.
     *
     * **Access points** - When you use this action with an access point, you must provide the alias of the access point in
     * place of the bucket name or specify the access point ARN. When using the access point ARN, you must direct requests
     * to the access point hostname. The access point hostname takes the form
     * *AccessPointName*-*AccountId*.s3-accesspoint.*Region*.amazonaws.com. When using this action with an access point
     * through the Amazon Web Services SDKs, you provide the access point ARN in place of the bucket name. For more
     * information about access point ARNs, see Using access points [^2] in the *Amazon S3 User Guide*.
     *
     * > Access points and Object Lambda access points are not supported by directory buckets.
     *
     * **S3 on Outposts** - When you use this action with Amazon S3 on Outposts, you must direct requests to the S3 on
     * Outposts hostname. The S3 on Outposts hostname takes the form
     * `*AccessPointName*-*AccountId*.*outpostID*.s3-outposts.*Region*.amazonaws.com`. When you use this action with S3 on
     * Outposts through the Amazon Web Services SDKs, you provide the Outposts access point ARN in place of the bucket name.
     * For more information about S3 on Outposts ARNs, see What is S3 on Outposts? [^3] in the *Amazon S3 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/directory-bucket-naming-rules.html
     * [^2]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/using-access-points.html
     * [^3]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/S3onOutposts.html
     *
     * @required
     *
     * @var string|null
     */
    private $bucket;

    /**
     * Key name of the object to delete.
     *
     * @required
     *
     * @var string|null
     */
    private $key;

    /**
     * The concatenation of the authentication device's serial number, a space, and the value that is displayed on your
     * authentication device. Required to permanently delete a versioned object if versioning is configured with MFA delete
     * enabled.
     *
     * > This functionality is not supported for directory buckets.
     *
     * @var string|null
     */
    private $mfa;

    /**
     * Version ID used to reference a specific version of the object.
     *
     * > For directory buckets in this API operation, only the `null` value of the version ID is supported.
     *
     * @var string|null
     */
    private $versionId;

    /**
     * @var RequestPayer::*|null
     */
    private $requestPayer;

    /**
     * Indicates whether S3 Object Lock should bypass Governance-mode restrictions to process this operation. To use this
     * header, you must have the `s3:BypassGovernanceRetention` permission.
     *
     * > This functionality is not supported for directory buckets.
     *
     * @var bool|null
     */
    private $bypassGovernanceRetention;

    /**
     * The account ID of the expected bucket owner. If the account ID that you provide does not match the actual owner of
     * the bucket, the request fails with the HTTP status code `403 Forbidden` (access denied).
     *
     * @var string|null
     */
    private $expectedBucketOwner;

    /**
     * The `If-Match` header field makes the request method conditional on ETags. If the ETag value does not match, the
     * operation returns a `412 Precondition Failed` error. If the ETag matches or if the object doesn't exist, the
     * operation will return a `204 Success (No Content) response`.
     *
     * For more information about conditional requests, see RFC 7232 [^1].
     *
     * > This functionality is only supported for directory buckets.
     *
     * [^1]: https://docs.aws.amazon.com/https:/tools.ietf.org/html/rfc7232
     *
     * @var string|null
     */
    private $ifMatch;

    /**
     * If present, the object is deleted only if its modification times matches the provided `Timestamp`. If the `Timestamp`
     * values do not match, the operation returns a `412 Precondition Failed` error. If the `Timestamp` matches or if the
     * object doesn’t exist, the operation returns a `204 Success (No Content)` response.
     *
     * > This functionality is only supported for directory buckets.
     *
     * @var \DateTimeImmutable|null
     */
    private $ifMatchLastModifiedTime;

    /**
     * If present, the object is deleted only if its size matches the provided size in bytes. If the `Size` value does not
     * match, the operation returns a `412 Precondition Failed` error. If the `Size` matches or if the object doesn’t
     * exist, the operation returns a `204 Success (No Content)` response.
     *
     * > This functionality is only supported for directory buckets.
     *
     * ! You can use the `If-Match`, `x-amz-if-match-last-modified-time` and `x-amz-if-match-size` conditional headers in
     * ! conjunction with each-other or individually.
     *
     * @var int|null
     */
    private $ifMatchSize;

    /**
     * @param array{
     *   Bucket?: string,
     *   Key?: string,
     *   MFA?: null|string,
     *   VersionId?: null|string,
     *   RequestPayer?: null|RequestPayer::*,
     *   BypassGovernanceRetention?: null|bool,
     *   ExpectedBucketOwner?: null|string,
     *   IfMatch?: null|string,
     *   IfMatchLastModifiedTime?: null|\DateTimeImmutable|string,
     *   IfMatchSize?: null|int,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->bucket = $input['Bucket'] ?? null;
        $this->key = $input['Key'] ?? null;
        $this->mfa = $input['MFA'] ?? null;
        $this->versionId = $input['VersionId'] ?? null;
        $this->requestPayer = $input['RequestPayer'] ?? null;
        $this->bypassGovernanceRetention = $input['BypassGovernanceRetention'] ?? null;
        $this->expectedBucketOwner = $input['ExpectedBucketOwner'] ?? null;
        $this->ifMatch = $input['IfMatch'] ?? null;
        $this->ifMatchLastModifiedTime = !isset($input['IfMatchLastModifiedTime']) ? null : ($input['IfMatchLastModifiedTime'] instanceof \DateTimeImmutable ? $input['IfMatchLastModifiedTime'] : new \DateTimeImmutable($input['IfMatchLastModifiedTime']));
        $this->ifMatchSize = $input['IfMatchSize'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   Bucket?: string,
     *   Key?: string,
     *   MFA?: null|string,
     *   VersionId?: null|string,
     *   RequestPayer?: null|RequestPayer::*,
     *   BypassGovernanceRetention?: null|bool,
     *   ExpectedBucketOwner?: null|string,
     *   IfMatch?: null|string,
     *   IfMatchLastModifiedTime?: null|\DateTimeImmutable|string,
     *   IfMatchSize?: null|int,
     *   '@region'?: string|null,
     * }|DeleteObjectRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getBucket(): ?string
    {
        return $this->bucket;
    }

    public function getBypassGovernanceRetention(): ?bool
    {
        return $this->bypassGovernanceRetention;
    }

    public function getExpectedBucketOwner(): ?string
    {
        return $this->expectedBucketOwner;
    }

    public function getIfMatch(): ?string
    {
        return $this->ifMatch;
    }

    public function getIfMatchLastModifiedTime(): ?\DateTimeImmutable
    {
        return $this->ifMatchLastModifiedTime;
    }

    public function getIfMatchSize(): ?int
    {
        return $this->ifMatchSize;
    }

    public function getKey(): ?string
    {
        return $this->key;
    }

    public function getMfa(): ?string
    {
        return $this->mfa;
    }

    /**
     * @return RequestPayer::*|null
     */
    public function getRequestPayer(): ?string
    {
        return $this->requestPayer;
    }

    public function getVersionId(): ?string
    {
        return $this->versionId;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = ['content-type' => 'application/xml'];
        if (null !== $this->mfa) {
            $headers['x-amz-mfa'] = $this->mfa;
        }
        if (null !== $this->requestPayer) {
            if (!RequestPayer::exists($this->requestPayer)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "RequestPayer" for "%s". The value "%s" is not a valid "RequestPayer".', __CLASS__, $this->requestPayer));
            }
            $headers['x-amz-request-payer'] = $this->requestPayer;
        }
        if (null !== $this->bypassGovernanceRetention) {
            $headers['x-amz-bypass-governance-retention'] = $this->bypassGovernanceRetention ? 'true' : 'false';
        }
        if (null !== $this->expectedBucketOwner) {
            $headers['x-amz-expected-bucket-owner'] = $this->expectedBucketOwner;
        }
        if (null !== $this->ifMatch) {
            $headers['If-Match'] = $this->ifMatch;
        }
        if (null !== $this->ifMatchLastModifiedTime) {
            $headers['x-amz-if-match-last-modified-time'] = $this->ifMatchLastModifiedTime->setTimezone(new \DateTimeZone('GMT'))->format(\DateTimeInterface::RFC7231);
        }
        if (null !== $this->ifMatchSize) {
            $headers['x-amz-if-match-size'] = (string) $this->ifMatchSize;
        }

        // Prepare query
        $query = [];
        if (null !== $this->versionId) {
            $query['versionId'] = $this->versionId;
        }

        // Prepare URI
        $uri = [];
        if (null === $v = $this->bucket) {
            throw new InvalidArgument(\sprintf('Missing parameter "Bucket" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['Bucket'] = $v;
        if (null === $v = $this->key) {
            throw new InvalidArgument(\sprintf('Missing parameter "Key" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['Key'] = $v;
        $uriString = '/' . rawurlencode($uri['Bucket']) . '/' . str_replace('%2F', '/', rawurlencode($uri['Key']));

        // Prepare Body
        $body = '';

        // Return the Request
        return new Request('DELETE', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setBucket(?string $value): self
    {
        $this->bucket = $value;

        return $this;
    }

    public function setBypassGovernanceRetention(?bool $value): self
    {
        $this->bypassGovernanceRetention = $value;

        return $this;
    }

    public function setExpectedBucketOwner(?string $value): self
    {
        $this->expectedBucketOwner = $value;

        return $this;
    }

    public function setIfMatch(?string $value): self
    {
        $this->ifMatch = $value;

        return $this;
    }

    public function setIfMatchLastModifiedTime(?\DateTimeImmutable $value): self
    {
        $this->ifMatchLastModifiedTime = $value;

        return $this;
    }

    public function setIfMatchSize(?int $value): self
    {
        $this->ifMatchSize = $value;

        return $this;
    }

    public function setKey(?string $value): self
    {
        $this->key = $value;

        return $this;
    }

    public function setMfa(?string $value): self
    {
        $this->mfa = $value;

        return $this;
    }

    /**
     * @param RequestPayer::*|null $value
     */
    public function setRequestPayer(?string $value): self
    {
        $this->requestPayer = $value;

        return $this;
    }

    public function setVersionId(?string $value): self
    {
        $this->versionId = $value;

        return $this;
    }
}
