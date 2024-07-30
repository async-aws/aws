<?php

namespace AsyncAws\S3\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class DeleteBucketRequest extends Input
{
    /**
     * Specifies the bucket being deleted.
     *
     * **Directory buckets ** - When you use this operation with a directory bucket, you must use path-style requests in the
     * format `https://s3express-control.*region_code*.amazonaws.com/*bucket-name*`. Virtual-hosted-style requests aren't
     * supported. Directory bucket names must be unique in the chosen Availability Zone. Bucket names must also follow the
     * format `*bucket_base_name*--*az_id*--x-s3` (for example, `*DOC-EXAMPLE-BUCKET*--*usw2-az1*--x-s3`). For information
     * about bucket naming restrictions, see Directory bucket naming rules [^1] in the *Amazon S3 User Guide*
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/directory-bucket-naming-rules.html
     *
     * @required
     *
     * @var string|null
     */
    private $bucket;

    /**
     * The account ID of the expected bucket owner. If the account ID that you provide does not match the actual owner of
     * the bucket, the request fails with the HTTP status code `403 Forbidden` (access denied).
     *
     * > For directory buckets, this header is not supported in this API operation. If you specify this header, the request
     * > fails with the HTTP status code `501 Not Implemented`.
     *
     * @var string|null
     */
    private $expectedBucketOwner;

    /**
     * @param array{
     *   Bucket?: string,
     *   ExpectedBucketOwner?: null|string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->bucket = $input['Bucket'] ?? null;
        $this->expectedBucketOwner = $input['ExpectedBucketOwner'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   Bucket?: string,
     *   ExpectedBucketOwner?: null|string,
     *   '@region'?: string|null,
     * }|DeleteBucketRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getBucket(): ?string
    {
        return $this->bucket;
    }

    public function getExpectedBucketOwner(): ?string
    {
        return $this->expectedBucketOwner;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = ['content-type' => 'application/xml'];
        if (null !== $this->expectedBucketOwner) {
            $headers['x-amz-expected-bucket-owner'] = $this->expectedBucketOwner;
        }

        // Prepare query
        $query = [];

        // Prepare URI
        $uri = [];
        if (null === $v = $this->bucket) {
            throw new InvalidArgument(\sprintf('Missing parameter "Bucket" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['Bucket'] = $v;
        $uriString = '/' . rawurlencode($uri['Bucket']);

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

    public function setExpectedBucketOwner(?string $value): self
    {
        $this->expectedBucketOwner = $value;

        return $this;
    }
}
