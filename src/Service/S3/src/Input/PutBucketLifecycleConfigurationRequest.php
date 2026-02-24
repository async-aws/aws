<?php

namespace AsyncAws\S3\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\S3\Enum\ChecksumAlgorithm;
use AsyncAws\S3\Enum\TransitionDefaultMinimumObjectSize;
use AsyncAws\S3\ValueObject\BucketLifecycleConfiguration;

final class PutBucketLifecycleConfigurationRequest extends Input
{
    /**
     * The name of the bucket for which to set the configuration.
     *
     * @required
     *
     * @var string|null
     */
    private $bucket;

    /**
     * Indicates the algorithm used to create the checksum for the request when you use the SDK. This header will not
     * provide any additional functionality if you don't use the SDK. When you send this header, there must be a
     * corresponding `x-amz-checksum` or `x-amz-trailer` header sent. Otherwise, Amazon S3 fails the request with the HTTP
     * status code `400 Bad Request`. For more information, see Checking object integrity [^1] in the *Amazon S3 User
     * Guide*.
     *
     * If you provide an individual checksum, Amazon S3 ignores any provided `ChecksumAlgorithm` parameter.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/checking-object-integrity.html
     *
     * @var ChecksumAlgorithm::*|null
     */
    private $checksumAlgorithm;

    /**
     * Container for lifecycle rules. You can add as many as 1,000 rules.
     *
     * @var BucketLifecycleConfiguration|null
     */
    private $lifecycleConfiguration;

    /**
     * The account ID of the expected bucket owner. If the account ID that you provide does not match the actual owner of
     * the bucket, the request fails with the HTTP status code `403 Forbidden` (access denied).
     *
     * > This parameter applies to general purpose buckets only. It is not supported for directory bucket lifecycle
     * > configurations.
     *
     * @var string|null
     */
    private $expectedBucketOwner;

    /**
     * Indicates which default minimum object size behavior is applied to the lifecycle configuration.
     *
     * > This parameter applies to general purpose buckets only. It is not supported for directory bucket lifecycle
     * > configurations.
     *
     * - `all_storage_classes_128K` - Objects smaller than 128 KB will not transition to any storage class by default.
     * - `varies_by_storage_class` - Objects smaller than 128 KB will transition to Glacier Flexible Retrieval or Glacier
     *   Deep Archive storage classes. By default, all other storage classes will prevent transitions smaller than 128 KB.
     *
     * To customize the minimum object size for any transition you can add a filter that specifies a custom
     * `ObjectSizeGreaterThan` or `ObjectSizeLessThan` in the body of your transition rule. Custom filters always take
     * precedence over the default transition behavior.
     *
     * @var TransitionDefaultMinimumObjectSize::*|null
     */
    private $transitionDefaultMinimumObjectSize;

    /**
     * @param array{
     *   Bucket?: string,
     *   ChecksumAlgorithm?: ChecksumAlgorithm::*|null,
     *   LifecycleConfiguration?: BucketLifecycleConfiguration|array|null,
     *   ExpectedBucketOwner?: string|null,
     *   TransitionDefaultMinimumObjectSize?: TransitionDefaultMinimumObjectSize::*|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->bucket = $input['Bucket'] ?? null;
        $this->checksumAlgorithm = $input['ChecksumAlgorithm'] ?? null;
        $this->lifecycleConfiguration = isset($input['LifecycleConfiguration']) ? BucketLifecycleConfiguration::create($input['LifecycleConfiguration']) : null;
        $this->expectedBucketOwner = $input['ExpectedBucketOwner'] ?? null;
        $this->transitionDefaultMinimumObjectSize = $input['TransitionDefaultMinimumObjectSize'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   Bucket?: string,
     *   ChecksumAlgorithm?: ChecksumAlgorithm::*|null,
     *   LifecycleConfiguration?: BucketLifecycleConfiguration|array|null,
     *   ExpectedBucketOwner?: string|null,
     *   TransitionDefaultMinimumObjectSize?: TransitionDefaultMinimumObjectSize::*|null,
     *   '@region'?: string|null,
     * }|PutBucketLifecycleConfigurationRequest $input
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

    public function getExpectedBucketOwner(): ?string
    {
        return $this->expectedBucketOwner;
    }

    public function getLifecycleConfiguration(): ?BucketLifecycleConfiguration
    {
        return $this->lifecycleConfiguration;
    }

    /**
     * @return TransitionDefaultMinimumObjectSize::*|null
     */
    public function getTransitionDefaultMinimumObjectSize(): ?string
    {
        return $this->transitionDefaultMinimumObjectSize;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = ['content-type' => 'application/xml'];
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
        if (null !== $this->transitionDefaultMinimumObjectSize) {
            if (!TransitionDefaultMinimumObjectSize::exists($this->transitionDefaultMinimumObjectSize)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "TransitionDefaultMinimumObjectSize" for "%s". The value "%s" is not a valid "TransitionDefaultMinimumObjectSize".', __CLASS__, $this->transitionDefaultMinimumObjectSize));
            }
            $headers['x-amz-transition-default-minimum-object-size'] = $this->transitionDefaultMinimumObjectSize;
        }

        // Prepare query
        $query = [];

        // Prepare URI
        $uri = [];
        if (null === $v = $this->bucket) {
            throw new InvalidArgument(\sprintf('Missing parameter "Bucket" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['Bucket'] = $v;
        $uriString = '/' . rawurlencode($uri['Bucket']) . '?lifecycle';

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

    public function setExpectedBucketOwner(?string $value): self
    {
        $this->expectedBucketOwner = $value;

        return $this;
    }

    public function setLifecycleConfiguration(?BucketLifecycleConfiguration $value): self
    {
        $this->lifecycleConfiguration = $value;

        return $this;
    }

    /**
     * @param TransitionDefaultMinimumObjectSize::*|null $value
     */
    public function setTransitionDefaultMinimumObjectSize(?string $value): self
    {
        $this->transitionDefaultMinimumObjectSize = $value;

        return $this;
    }

    private function requestBody(\DOMNode $node, \DOMDocument $document): void
    {
        if (null !== $v = $this->lifecycleConfiguration) {
            $node->appendChild($child = $document->createElement('LifecycleConfiguration'));
            $child->setAttribute('xmlns', 'http://s3.amazonaws.com/doc/2006-03-01/');
            $v->requestBody($child, $document);
        }
    }
}
