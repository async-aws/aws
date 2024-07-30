<?php

namespace AsyncAws\S3\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\S3\Enum\EncodingType;
use AsyncAws\S3\Enum\OptionalObjectAttributes;
use AsyncAws\S3\Enum\RequestPayer;

final class ListObjectVersionsRequest extends Input
{
    /**
     * The bucket name that contains the objects.
     *
     * @required
     *
     * @var string|null
     */
    private $bucket;

    /**
     * A delimiter is a character that you specify to group keys. All keys that contain the same string between the `prefix`
     * and the first occurrence of the delimiter are grouped under a single result element in `CommonPrefixes`. These groups
     * are counted as one result against the `max-keys` limitation. These keys are not returned elsewhere in the response.
     *
     * @var string|null
     */
    private $delimiter;

    /**
     * @var EncodingType::*|null
     */
    private $encodingType;

    /**
     * Specifies the key to start with when listing objects in a bucket.
     *
     * @var string|null
     */
    private $keyMarker;

    /**
     * Sets the maximum number of keys returned in the response. By default, the action returns up to 1,000 key names. The
     * response might contain fewer keys but will never contain more. If additional keys satisfy the search criteria, but
     * were not returned because `max-keys` was exceeded, the response contains
     * `<isTruncated>true</isTruncated>`. To return the additional keys, see `key-marker` and
     * `version-id-marker`.
     *
     * @var int|null
     */
    private $maxKeys;

    /**
     * Use this parameter to select only those keys that begin with the specified prefix. You can use prefixes to separate a
     * bucket into different groupings of keys. (You can think of using `prefix` to make groups in the same way that you'd
     * use a folder in a file system.) You can use `prefix` with `delimiter` to roll up numerous objects into a single
     * result under `CommonPrefixes`.
     *
     * @var string|null
     */
    private $prefix;

    /**
     * Specifies the object version you want to start listing from.
     *
     * @var string|null
     */
    private $versionIdMarker;

    /**
     * The account ID of the expected bucket owner. If the account ID that you provide does not match the actual owner of
     * the bucket, the request fails with the HTTP status code `403 Forbidden` (access denied).
     *
     * @var string|null
     */
    private $expectedBucketOwner;

    /**
     * @var RequestPayer::*|null
     */
    private $requestPayer;

    /**
     * Specifies the optional fields that you want returned in the response. Fields that you do not specify are not
     * returned.
     *
     * @var list<OptionalObjectAttributes::*>|null
     */
    private $optionalObjectAttributes;

    /**
     * @param array{
     *   Bucket?: string,
     *   Delimiter?: null|string,
     *   EncodingType?: null|EncodingType::*,
     *   KeyMarker?: null|string,
     *   MaxKeys?: null|int,
     *   Prefix?: null|string,
     *   VersionIdMarker?: null|string,
     *   ExpectedBucketOwner?: null|string,
     *   RequestPayer?: null|RequestPayer::*,
     *   OptionalObjectAttributes?: null|array<OptionalObjectAttributes::*>,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->bucket = $input['Bucket'] ?? null;
        $this->delimiter = $input['Delimiter'] ?? null;
        $this->encodingType = $input['EncodingType'] ?? null;
        $this->keyMarker = $input['KeyMarker'] ?? null;
        $this->maxKeys = $input['MaxKeys'] ?? null;
        $this->prefix = $input['Prefix'] ?? null;
        $this->versionIdMarker = $input['VersionIdMarker'] ?? null;
        $this->expectedBucketOwner = $input['ExpectedBucketOwner'] ?? null;
        $this->requestPayer = $input['RequestPayer'] ?? null;
        $this->optionalObjectAttributes = $input['OptionalObjectAttributes'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   Bucket?: string,
     *   Delimiter?: null|string,
     *   EncodingType?: null|EncodingType::*,
     *   KeyMarker?: null|string,
     *   MaxKeys?: null|int,
     *   Prefix?: null|string,
     *   VersionIdMarker?: null|string,
     *   ExpectedBucketOwner?: null|string,
     *   RequestPayer?: null|RequestPayer::*,
     *   OptionalObjectAttributes?: null|array<OptionalObjectAttributes::*>,
     *   '@region'?: string|null,
     * }|ListObjectVersionsRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getBucket(): ?string
    {
        return $this->bucket;
    }

    public function getDelimiter(): ?string
    {
        return $this->delimiter;
    }

    /**
     * @return EncodingType::*|null
     */
    public function getEncodingType(): ?string
    {
        return $this->encodingType;
    }

    public function getExpectedBucketOwner(): ?string
    {
        return $this->expectedBucketOwner;
    }

    public function getKeyMarker(): ?string
    {
        return $this->keyMarker;
    }

    public function getMaxKeys(): ?int
    {
        return $this->maxKeys;
    }

    /**
     * @return list<OptionalObjectAttributes::*>
     */
    public function getOptionalObjectAttributes(): array
    {
        return $this->optionalObjectAttributes ?? [];
    }

    public function getPrefix(): ?string
    {
        return $this->prefix;
    }

    /**
     * @return RequestPayer::*|null
     */
    public function getRequestPayer(): ?string
    {
        return $this->requestPayer;
    }

    public function getVersionIdMarker(): ?string
    {
        return $this->versionIdMarker;
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
        if (null !== $this->requestPayer) {
            if (!RequestPayer::exists($this->requestPayer)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "RequestPayer" for "%s". The value "%s" is not a valid "RequestPayer".', __CLASS__, $this->requestPayer));
            }
            $headers['x-amz-request-payer'] = $this->requestPayer;
        }
        if (null !== $this->optionalObjectAttributes) {
            $items = [];
            foreach ($this->optionalObjectAttributes as $value) {
                if (!OptionalObjectAttributes::exists($value)) {
                    throw new InvalidArgument(\sprintf('Invalid parameter "OptionalObjectAttributes" for "%s". The value "%s" is not a valid "OptionalObjectAttributes".', __CLASS__, $value));
                }
                $items[] = $value;
            }
            $headers['x-amz-optional-object-attributes'] = implode(',', $items);
        }

        // Prepare query
        $query = [];
        if (null !== $this->delimiter) {
            $query['delimiter'] = $this->delimiter;
        }
        if (null !== $this->encodingType) {
            if (!EncodingType::exists($this->encodingType)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "EncodingType" for "%s". The value "%s" is not a valid "EncodingType".', __CLASS__, $this->encodingType));
            }
            $query['encoding-type'] = $this->encodingType;
        }
        if (null !== $this->keyMarker) {
            $query['key-marker'] = $this->keyMarker;
        }
        if (null !== $this->maxKeys) {
            $query['max-keys'] = (string) $this->maxKeys;
        }
        if (null !== $this->prefix) {
            $query['prefix'] = $this->prefix;
        }
        if (null !== $this->versionIdMarker) {
            $query['version-id-marker'] = $this->versionIdMarker;
        }

        // Prepare URI
        $uri = [];
        if (null === $v = $this->bucket) {
            throw new InvalidArgument(\sprintf('Missing parameter "Bucket" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['Bucket'] = $v;
        $uriString = '/' . rawurlencode($uri['Bucket']) . '?versions';

        // Prepare Body
        $body = '';

        // Return the Request
        return new Request('GET', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setBucket(?string $value): self
    {
        $this->bucket = $value;

        return $this;
    }

    public function setDelimiter(?string $value): self
    {
        $this->delimiter = $value;

        return $this;
    }

    /**
     * @param EncodingType::*|null $value
     */
    public function setEncodingType(?string $value): self
    {
        $this->encodingType = $value;

        return $this;
    }

    public function setExpectedBucketOwner(?string $value): self
    {
        $this->expectedBucketOwner = $value;

        return $this;
    }

    public function setKeyMarker(?string $value): self
    {
        $this->keyMarker = $value;

        return $this;
    }

    public function setMaxKeys(?int $value): self
    {
        $this->maxKeys = $value;

        return $this;
    }

    /**
     * @param list<OptionalObjectAttributes::*> $value
     */
    public function setOptionalObjectAttributes(array $value): self
    {
        $this->optionalObjectAttributes = $value;

        return $this;
    }

    public function setPrefix(?string $value): self
    {
        $this->prefix = $value;

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

    public function setVersionIdMarker(?string $value): self
    {
        $this->versionIdMarker = $value;

        return $this;
    }
}
