<?php

namespace AsyncAws\S3\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\S3\Enum\RequestPayer;

final class UploadPartCopyRequest extends Input
{
    /**
     * The bucket name.
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
     * Specifies the source object for the copy operation. You specify the value in one of two formats, depending on whether
     * you want to access the source object through an access point [^1]:
     *
     * - For objects not accessed through an access point, specify the name of the source bucket and key of the source
     *   object, separated by a slash (/). For example, to copy the object `reports/january.pdf` from the bucket
     *   `awsexamplebucket`, use `awsexamplebucket/reports/january.pdf`. The value must be URL-encoded.
     * - For objects accessed through access points, specify the Amazon Resource Name (ARN) of the object as accessed
     *   through the access point, in the format
     *   `arn:aws:s3:<Region>:<account-id>:accesspoint/<access-point-name>/object/<key>`. For
     *   example, to copy the object `reports/january.pdf` through access point `my-access-point` owned by account
     *   `123456789012` in Region `us-west-2`, use the URL encoding of
     *   `arn:aws:s3:us-west-2:123456789012:accesspoint/my-access-point/object/reports/january.pdf`. The value must be URL
     *   encoded.
     *
     *   > - Amazon S3 supports copy operations using Access points only when the source and destination buckets are in the
     *   >   same Amazon Web Services Region.
     *   > - Access points are not supported by directory buckets.
     *   >
     *
     *   Alternatively, for objects accessed through Amazon S3 on Outposts, specify the ARN of the object as accessed in the
     *   format `arn:aws:s3-outposts:<Region>:<account-id>:outpost/<outpost-id>/object/<key>`. For
     *   example, to copy the object `reports/january.pdf` through outpost `my-outpost` owned by account `123456789012` in
     *   Region `us-west-2`, use the URL encoding of
     *   `arn:aws:s3-outposts:us-west-2:123456789012:outpost/my-outpost/object/reports/january.pdf`. The value must be
     *   URL-encoded.
     *
     * If your bucket has versioning enabled, you could have multiple versions of the same object. By default,
     * `x-amz-copy-source` identifies the current version of the source object to copy. To copy a specific version of the
     * source object to copy, append `?versionId=<version-id>` to the `x-amz-copy-source` request header (for example,
     * `x-amz-copy-source: /awsexamplebucket/reports/january.pdf?versionId=QUpfdndhfd8438MNFDN93jdnJFkdmqnh893`).
     *
     * If the current version is a delete marker and you don't specify a versionId in the `x-amz-copy-source` request
     * header, Amazon S3 returns a `404 Not Found` error, because the object does not exist. If you specify versionId in the
     * `x-amz-copy-source` and the versionId is a delete marker, Amazon S3 returns an HTTP `400 Bad Request` error, because
     * you are not allowed to specify a delete marker as a version for the `x-amz-copy-source`.
     *
     * > **Directory buckets** - S3 Versioning isn't enabled and supported for directory buckets.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/access-points.html
     *
     * @required
     *
     * @var string|null
     */
    private $copySource;

    /**
     * Copies the object if its entity tag (ETag) matches the specified tag.
     *
     * If both of the `x-amz-copy-source-if-match` and `x-amz-copy-source-if-unmodified-since` headers are present in the
     * request as follows:
     *
     * `x-amz-copy-source-if-match` condition evaluates to `true`, and;
     *
     * `x-amz-copy-source-if-unmodified-since` condition evaluates to `false`;
     *
     * Amazon S3 returns `200 OK` and copies the data.
     *
     * @var string|null
     */
    private $copySourceIfMatch;

    /**
     * Copies the object if it has been modified since the specified time.
     *
     * If both of the `x-amz-copy-source-if-none-match` and `x-amz-copy-source-if-modified-since` headers are present in the
     * request as follows:
     *
     * `x-amz-copy-source-if-none-match` condition evaluates to `false`, and;
     *
     * `x-amz-copy-source-if-modified-since` condition evaluates to `true`;
     *
     * Amazon S3 returns `412 Precondition Failed` response code.
     *
     * @var \DateTimeImmutable|null
     */
    private $copySourceIfModifiedSince;

    /**
     * Copies the object if its entity tag (ETag) is different than the specified ETag.
     *
     * If both of the `x-amz-copy-source-if-none-match` and `x-amz-copy-source-if-modified-since` headers are present in the
     * request as follows:
     *
     * `x-amz-copy-source-if-none-match` condition evaluates to `false`, and;
     *
     * `x-amz-copy-source-if-modified-since` condition evaluates to `true`;
     *
     * Amazon S3 returns `412 Precondition Failed` response code.
     *
     * @var string|null
     */
    private $copySourceIfNoneMatch;

    /**
     * Copies the object if it hasn't been modified since the specified time.
     *
     * If both of the `x-amz-copy-source-if-match` and `x-amz-copy-source-if-unmodified-since` headers are present in the
     * request as follows:
     *
     * `x-amz-copy-source-if-match` condition evaluates to `true`, and;
     *
     * `x-amz-copy-source-if-unmodified-since` condition evaluates to `false`;
     *
     * Amazon S3 returns `200 OK` and copies the data.
     *
     * @var \DateTimeImmutable|null
     */
    private $copySourceIfUnmodifiedSince;

    /**
     * The range of bytes to copy from the source object. The range value must use the form bytes=first-last, where the
     * first and last are the zero-based byte offsets to copy. For example, bytes=0-9 indicates that you want to copy the
     * first 10 bytes of the source. You can copy a range only if the source object is greater than 5 MB.
     *
     * @var string|null
     */
    private $copySourceRange;

    /**
     * Object key for which the multipart upload was initiated.
     *
     * @required
     *
     * @var string|null
     */
    private $key;

    /**
     * Part number of part being copied. This is a positive integer between 1 and 10,000.
     *
     * @required
     *
     * @var int|null
     */
    private $partNumber;

    /**
     * Upload ID identifying the multipart upload whose part is being copied.
     *
     * @required
     *
     * @var string|null
     */
    private $uploadId;

    /**
     * Specifies the algorithm to use when encrypting the object (for example, AES256).
     *
     * > This functionality is not supported when the destination bucket is a directory bucket.
     *
     * @var string|null
     */
    private $sseCustomerAlgorithm;

    /**
     * Specifies the customer-provided encryption key for Amazon S3 to use in encrypting data. This value is used to store
     * the object and then it is discarded; Amazon S3 does not store the encryption key. The key must be appropriate for use
     * with the algorithm specified in the `x-amz-server-side-encryption-customer-algorithm` header. This must be the same
     * encryption key specified in the initiate multipart upload request.
     *
     * > This functionality is not supported when the destination bucket is a directory bucket.
     *
     * @var string|null
     */
    private $sseCustomerKey;

    /**
     * Specifies the 128-bit MD5 digest of the encryption key according to RFC 1321. Amazon S3 uses this header for a
     * message integrity check to ensure that the encryption key was transmitted without error.
     *
     * > This functionality is not supported when the destination bucket is a directory bucket.
     *
     * @var string|null
     */
    private $sseCustomerKeyMd5;

    /**
     * Specifies the algorithm to use when decrypting the source object (for example, `AES256`).
     *
     * > This functionality is not supported when the source object is in a directory bucket.
     *
     * @var string|null
     */
    private $copySourceSseCustomerAlgorithm;

    /**
     * Specifies the customer-provided encryption key for Amazon S3 to use to decrypt the source object. The encryption key
     * provided in this header must be one that was used when the source object was created.
     *
     * > This functionality is not supported when the source object is in a directory bucket.
     *
     * @var string|null
     */
    private $copySourceSseCustomerKey;

    /**
     * Specifies the 128-bit MD5 digest of the encryption key according to RFC 1321. Amazon S3 uses this header for a
     * message integrity check to ensure that the encryption key was transmitted without error.
     *
     * > This functionality is not supported when the source object is in a directory bucket.
     *
     * @var string|null
     */
    private $copySourceSseCustomerKeyMd5;

    /**
     * @var RequestPayer::*|null
     */
    private $requestPayer;

    /**
     * The account ID of the expected destination bucket owner. If the account ID that you provide does not match the actual
     * owner of the destination bucket, the request fails with the HTTP status code `403 Forbidden` (access denied).
     *
     * @var string|null
     */
    private $expectedBucketOwner;

    /**
     * The account ID of the expected source bucket owner. If the account ID that you provide does not match the actual
     * owner of the source bucket, the request fails with the HTTP status code `403 Forbidden` (access denied).
     *
     * @var string|null
     */
    private $expectedSourceBucketOwner;

    /**
     * @param array{
     *   Bucket?: string,
     *   CopySource?: string,
     *   CopySourceIfMatch?: null|string,
     *   CopySourceIfModifiedSince?: null|\DateTimeImmutable|string,
     *   CopySourceIfNoneMatch?: null|string,
     *   CopySourceIfUnmodifiedSince?: null|\DateTimeImmutable|string,
     *   CopySourceRange?: null|string,
     *   Key?: string,
     *   PartNumber?: int,
     *   UploadId?: string,
     *   SSECustomerAlgorithm?: null|string,
     *   SSECustomerKey?: null|string,
     *   SSECustomerKeyMD5?: null|string,
     *   CopySourceSSECustomerAlgorithm?: null|string,
     *   CopySourceSSECustomerKey?: null|string,
     *   CopySourceSSECustomerKeyMD5?: null|string,
     *   RequestPayer?: null|RequestPayer::*,
     *   ExpectedBucketOwner?: null|string,
     *   ExpectedSourceBucketOwner?: null|string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->bucket = $input['Bucket'] ?? null;
        $this->copySource = $input['CopySource'] ?? null;
        $this->copySourceIfMatch = $input['CopySourceIfMatch'] ?? null;
        $this->copySourceIfModifiedSince = !isset($input['CopySourceIfModifiedSince']) ? null : ($input['CopySourceIfModifiedSince'] instanceof \DateTimeImmutable ? $input['CopySourceIfModifiedSince'] : new \DateTimeImmutable($input['CopySourceIfModifiedSince']));
        $this->copySourceIfNoneMatch = $input['CopySourceIfNoneMatch'] ?? null;
        $this->copySourceIfUnmodifiedSince = !isset($input['CopySourceIfUnmodifiedSince']) ? null : ($input['CopySourceIfUnmodifiedSince'] instanceof \DateTimeImmutable ? $input['CopySourceIfUnmodifiedSince'] : new \DateTimeImmutable($input['CopySourceIfUnmodifiedSince']));
        $this->copySourceRange = $input['CopySourceRange'] ?? null;
        $this->key = $input['Key'] ?? null;
        $this->partNumber = $input['PartNumber'] ?? null;
        $this->uploadId = $input['UploadId'] ?? null;
        $this->sseCustomerAlgorithm = $input['SSECustomerAlgorithm'] ?? null;
        $this->sseCustomerKey = $input['SSECustomerKey'] ?? null;
        $this->sseCustomerKeyMd5 = $input['SSECustomerKeyMD5'] ?? null;
        $this->copySourceSseCustomerAlgorithm = $input['CopySourceSSECustomerAlgorithm'] ?? null;
        $this->copySourceSseCustomerKey = $input['CopySourceSSECustomerKey'] ?? null;
        $this->copySourceSseCustomerKeyMd5 = $input['CopySourceSSECustomerKeyMD5'] ?? null;
        $this->requestPayer = $input['RequestPayer'] ?? null;
        $this->expectedBucketOwner = $input['ExpectedBucketOwner'] ?? null;
        $this->expectedSourceBucketOwner = $input['ExpectedSourceBucketOwner'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   Bucket?: string,
     *   CopySource?: string,
     *   CopySourceIfMatch?: null|string,
     *   CopySourceIfModifiedSince?: null|\DateTimeImmutable|string,
     *   CopySourceIfNoneMatch?: null|string,
     *   CopySourceIfUnmodifiedSince?: null|\DateTimeImmutable|string,
     *   CopySourceRange?: null|string,
     *   Key?: string,
     *   PartNumber?: int,
     *   UploadId?: string,
     *   SSECustomerAlgorithm?: null|string,
     *   SSECustomerKey?: null|string,
     *   SSECustomerKeyMD5?: null|string,
     *   CopySourceSSECustomerAlgorithm?: null|string,
     *   CopySourceSSECustomerKey?: null|string,
     *   CopySourceSSECustomerKeyMD5?: null|string,
     *   RequestPayer?: null|RequestPayer::*,
     *   ExpectedBucketOwner?: null|string,
     *   ExpectedSourceBucketOwner?: null|string,
     *   '@region'?: string|null,
     * }|UploadPartCopyRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getBucket(): ?string
    {
        return $this->bucket;
    }

    public function getCopySource(): ?string
    {
        return $this->copySource;
    }

    public function getCopySourceIfMatch(): ?string
    {
        return $this->copySourceIfMatch;
    }

    public function getCopySourceIfModifiedSince(): ?\DateTimeImmutable
    {
        return $this->copySourceIfModifiedSince;
    }

    public function getCopySourceIfNoneMatch(): ?string
    {
        return $this->copySourceIfNoneMatch;
    }

    public function getCopySourceIfUnmodifiedSince(): ?\DateTimeImmutable
    {
        return $this->copySourceIfUnmodifiedSince;
    }

    public function getCopySourceRange(): ?string
    {
        return $this->copySourceRange;
    }

    public function getCopySourceSseCustomerAlgorithm(): ?string
    {
        return $this->copySourceSseCustomerAlgorithm;
    }

    public function getCopySourceSseCustomerKey(): ?string
    {
        return $this->copySourceSseCustomerKey;
    }

    public function getCopySourceSseCustomerKeyMd5(): ?string
    {
        return $this->copySourceSseCustomerKeyMd5;
    }

    public function getExpectedBucketOwner(): ?string
    {
        return $this->expectedBucketOwner;
    }

    public function getExpectedSourceBucketOwner(): ?string
    {
        return $this->expectedSourceBucketOwner;
    }

    public function getKey(): ?string
    {
        return $this->key;
    }

    public function getPartNumber(): ?int
    {
        return $this->partNumber;
    }

    /**
     * @return RequestPayer::*|null
     */
    public function getRequestPayer(): ?string
    {
        return $this->requestPayer;
    }

    public function getSseCustomerAlgorithm(): ?string
    {
        return $this->sseCustomerAlgorithm;
    }

    public function getSseCustomerKey(): ?string
    {
        return $this->sseCustomerKey;
    }

    public function getSseCustomerKeyMd5(): ?string
    {
        return $this->sseCustomerKeyMd5;
    }

    public function getUploadId(): ?string
    {
        return $this->uploadId;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = ['content-type' => 'application/xml'];
        if (null === $v = $this->copySource) {
            throw new InvalidArgument(\sprintf('Missing parameter "CopySource" for "%s". The value cannot be null.', __CLASS__));
        }
        $headers['x-amz-copy-source'] = $v;
        if (null !== $this->copySourceIfMatch) {
            $headers['x-amz-copy-source-if-match'] = $this->copySourceIfMatch;
        }
        if (null !== $this->copySourceIfModifiedSince) {
            $headers['x-amz-copy-source-if-modified-since'] = $this->copySourceIfModifiedSince->setTimezone(new \DateTimeZone('GMT'))->format(\DateTimeInterface::RFC7231);
        }
        if (null !== $this->copySourceIfNoneMatch) {
            $headers['x-amz-copy-source-if-none-match'] = $this->copySourceIfNoneMatch;
        }
        if (null !== $this->copySourceIfUnmodifiedSince) {
            $headers['x-amz-copy-source-if-unmodified-since'] = $this->copySourceIfUnmodifiedSince->setTimezone(new \DateTimeZone('GMT'))->format(\DateTimeInterface::RFC7231);
        }
        if (null !== $this->copySourceRange) {
            $headers['x-amz-copy-source-range'] = $this->copySourceRange;
        }
        if (null !== $this->sseCustomerAlgorithm) {
            $headers['x-amz-server-side-encryption-customer-algorithm'] = $this->sseCustomerAlgorithm;
        }
        if (null !== $this->sseCustomerKey) {
            $headers['x-amz-server-side-encryption-customer-key'] = $this->sseCustomerKey;
        }
        if (null !== $this->sseCustomerKeyMd5) {
            $headers['x-amz-server-side-encryption-customer-key-MD5'] = $this->sseCustomerKeyMd5;
        }
        if (null !== $this->copySourceSseCustomerAlgorithm) {
            $headers['x-amz-copy-source-server-side-encryption-customer-algorithm'] = $this->copySourceSseCustomerAlgorithm;
        }
        if (null !== $this->copySourceSseCustomerKey) {
            $headers['x-amz-copy-source-server-side-encryption-customer-key'] = $this->copySourceSseCustomerKey;
        }
        if (null !== $this->copySourceSseCustomerKeyMd5) {
            $headers['x-amz-copy-source-server-side-encryption-customer-key-MD5'] = $this->copySourceSseCustomerKeyMd5;
        }
        if (null !== $this->requestPayer) {
            if (!RequestPayer::exists($this->requestPayer)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "RequestPayer" for "%s". The value "%s" is not a valid "RequestPayer".', __CLASS__, $this->requestPayer));
            }
            $headers['x-amz-request-payer'] = $this->requestPayer;
        }
        if (null !== $this->expectedBucketOwner) {
            $headers['x-amz-expected-bucket-owner'] = $this->expectedBucketOwner;
        }
        if (null !== $this->expectedSourceBucketOwner) {
            $headers['x-amz-source-expected-bucket-owner'] = $this->expectedSourceBucketOwner;
        }

        // Prepare query
        $query = [];
        if (null === $v = $this->partNumber) {
            throw new InvalidArgument(\sprintf('Missing parameter "PartNumber" for "%s". The value cannot be null.', __CLASS__));
        }
        $query['partNumber'] = (string) $v;
        if (null === $v = $this->uploadId) {
            throw new InvalidArgument(\sprintf('Missing parameter "UploadId" for "%s". The value cannot be null.', __CLASS__));
        }
        $query['uploadId'] = $v;

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
        return new Request('PUT', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setBucket(?string $value): self
    {
        $this->bucket = $value;

        return $this;
    }

    public function setCopySource(?string $value): self
    {
        $this->copySource = $value;

        return $this;
    }

    public function setCopySourceIfMatch(?string $value): self
    {
        $this->copySourceIfMatch = $value;

        return $this;
    }

    public function setCopySourceIfModifiedSince(?\DateTimeImmutable $value): self
    {
        $this->copySourceIfModifiedSince = $value;

        return $this;
    }

    public function setCopySourceIfNoneMatch(?string $value): self
    {
        $this->copySourceIfNoneMatch = $value;

        return $this;
    }

    public function setCopySourceIfUnmodifiedSince(?\DateTimeImmutable $value): self
    {
        $this->copySourceIfUnmodifiedSince = $value;

        return $this;
    }

    public function setCopySourceRange(?string $value): self
    {
        $this->copySourceRange = $value;

        return $this;
    }

    public function setCopySourceSseCustomerAlgorithm(?string $value): self
    {
        $this->copySourceSseCustomerAlgorithm = $value;

        return $this;
    }

    public function setCopySourceSseCustomerKey(?string $value): self
    {
        $this->copySourceSseCustomerKey = $value;

        return $this;
    }

    public function setCopySourceSseCustomerKeyMd5(?string $value): self
    {
        $this->copySourceSseCustomerKeyMd5 = $value;

        return $this;
    }

    public function setExpectedBucketOwner(?string $value): self
    {
        $this->expectedBucketOwner = $value;

        return $this;
    }

    public function setExpectedSourceBucketOwner(?string $value): self
    {
        $this->expectedSourceBucketOwner = $value;

        return $this;
    }

    public function setKey(?string $value): self
    {
        $this->key = $value;

        return $this;
    }

    public function setPartNumber(?int $value): self
    {
        $this->partNumber = $value;

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

    public function setSseCustomerAlgorithm(?string $value): self
    {
        $this->sseCustomerAlgorithm = $value;

        return $this;
    }

    public function setSseCustomerKey(?string $value): self
    {
        $this->sseCustomerKey = $value;

        return $this;
    }

    public function setSseCustomerKeyMd5(?string $value): self
    {
        $this->sseCustomerKeyMd5 = $value;

        return $this;
    }

    public function setUploadId(?string $value): self
    {
        $this->uploadId = $value;

        return $this;
    }
}
