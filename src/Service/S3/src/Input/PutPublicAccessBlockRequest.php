<?php

namespace AsyncAws\S3\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\S3\Enum\ChecksumAlgorithm;
use AsyncAws\S3\ValueObject\PublicAccessBlockConfiguration;

final class PutPublicAccessBlockRequest extends Input
{
    /**
     * The name of the Amazon S3 bucket whose `PublicAccessBlock` configuration you want to set.
     *
     * @required
     *
     * @var string|null
     */
    private $bucket;

    /**
     * The MD5 hash of the `PutPublicAccessBlock` request body.
     *
     * For requests made using the Amazon Web Services Command Line Interface (CLI) or Amazon Web Services SDKs, this field
     * is calculated automatically.
     *
     * @var string|null
     */
    private $contentMd5;

    /**
     * Indicates the algorithm used to create the checksum for the object when you use the SDK. This header will not provide
     * any additional functionality if you don't use the SDK. When you send this header, there must be a corresponding
     * `x-amz-checksum` or `x-amz-trailer` header sent. Otherwise, Amazon S3 fails the request with the HTTP status code
     * `400 Bad Request`. For more information, see Checking object integrity [^1] in the *Amazon S3 User Guide*.
     *
     * If you provide an individual checksum, Amazon S3 ignores any provided `ChecksumAlgorithm` parameter.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/checking-object-integrity.html
     *
     * @var ChecksumAlgorithm::*|null
     */
    private $checksumAlgorithm;

    /**
     * The `PublicAccessBlock` configuration that you want to apply to this Amazon S3 bucket. You can enable the
     * configuration options in any combination. For more information about when Amazon S3 considers a bucket or object
     * public, see The Meaning of "Public" [^1] in the *Amazon S3 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/dev/access-control-block-public-access.html#access-control-block-public-access-policy-status
     *
     * @required
     *
     * @var PublicAccessBlockConfiguration|null
     */
    private $publicAccessBlockConfiguration;

    /**
     * The account ID of the expected bucket owner. If the account ID that you provide does not match the actual owner of
     * the bucket, the request fails with the HTTP status code `403 Forbidden` (access denied).
     *
     * @var string|null
     */
    private $expectedBucketOwner;

    /**
     * @param array{
     *   Bucket?: string,
     *   ContentMD5?: string|null,
     *   ChecksumAlgorithm?: ChecksumAlgorithm::*|null,
     *   PublicAccessBlockConfiguration?: PublicAccessBlockConfiguration|array,
     *   ExpectedBucketOwner?: string|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->bucket = $input['Bucket'] ?? null;
        $this->contentMd5 = $input['ContentMD5'] ?? null;
        $this->checksumAlgorithm = $input['ChecksumAlgorithm'] ?? null;
        $this->publicAccessBlockConfiguration = isset($input['PublicAccessBlockConfiguration']) ? PublicAccessBlockConfiguration::create($input['PublicAccessBlockConfiguration']) : null;
        $this->expectedBucketOwner = $input['ExpectedBucketOwner'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   Bucket?: string,
     *   ContentMD5?: string|null,
     *   ChecksumAlgorithm?: ChecksumAlgorithm::*|null,
     *   PublicAccessBlockConfiguration?: PublicAccessBlockConfiguration|array,
     *   ExpectedBucketOwner?: string|null,
     *   '@region'?: string|null,
     * }|PutPublicAccessBlockRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getBucket(): ?string
    {
        return $this->bucket;
    }

    /**
     * @return ChecksumAlgorithm::*|null
     */
    public function getChecksumAlgorithm(): ?string
    {
        return $this->checksumAlgorithm;
    }

    public function getContentMd5(): ?string
    {
        return $this->contentMd5;
    }

    public function getExpectedBucketOwner(): ?string
    {
        return $this->expectedBucketOwner;
    }

    public function getPublicAccessBlockConfiguration(): ?PublicAccessBlockConfiguration
    {
        return $this->publicAccessBlockConfiguration;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = ['content-type' => 'application/xml'];
        if (null !== $this->contentMd5) {
            $headers['Content-MD5'] = $this->contentMd5;
        }
        if (null !== $this->checksumAlgorithm) {
            if (!ChecksumAlgorithm::exists($this->checksumAlgorithm)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "ChecksumAlgorithm" for "%s". The value "%s" is not a valid "ChecksumAlgorithm".', __CLASS__, $this->checksumAlgorithm));
            }
            $headers['x-amz-sdk-checksum-algorithm'] = $this->checksumAlgorithm;
        }
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
        $uriString = '/' . rawurlencode($uri['Bucket']) . '?publicAccessBlock';

        // Prepare Body

        $document = new \DOMDocument('1.0', 'UTF-8');
        $document->formatOutput = false;
        $this->requestBody($document, $document);
        $body = $document->hasChildNodes() ? $document->saveXML() : '';

        // Return the Request
        return new Request('PUT', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setBucket(?string $value): self
    {
        $this->bucket = $value;

        return $this;
    }

    /**
     * @param ChecksumAlgorithm::*|null $value
     */
    public function setChecksumAlgorithm(?string $value): self
    {
        $this->checksumAlgorithm = $value;

        return $this;
    }

    public function setContentMd5(?string $value): self
    {
        $this->contentMd5 = $value;

        return $this;
    }

    public function setExpectedBucketOwner(?string $value): self
    {
        $this->expectedBucketOwner = $value;

        return $this;
    }

    public function setPublicAccessBlockConfiguration(?PublicAccessBlockConfiguration $value): self
    {
        $this->publicAccessBlockConfiguration = $value;

        return $this;
    }

    private function requestBody(\DOMNode $node, \DOMDocument $document): void
    {
        if (null === $v = $this->publicAccessBlockConfiguration) {
            throw new InvalidArgument(\sprintf('Missing parameter "PublicAccessBlockConfiguration" for "%s". The value cannot be null.', __CLASS__));
        }

        $node->appendChild($child = $document->createElement('PublicAccessBlockConfiguration'));
        $child->setAttribute('xmlns', 'http://s3.amazonaws.com/doc/2006-03-01/');
        $v->requestBody($child, $document);
    }
}
