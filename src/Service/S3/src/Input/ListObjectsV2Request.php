<?php

namespace AsyncAws\S3\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\S3\Enum\EncodingType;
use AsyncAws\S3\Enum\OptionalObjectAttributes;
use AsyncAws\S3\Enum\RequestPayer;

final class ListObjectsV2Request extends Input
{
    /**
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
     * A delimiter is a character that you use to group keys.
     *
     * > - **Directory buckets** - For directory buckets, `/` is the only supported delimiter.
     * > - **Directory buckets ** - When you query `ListObjectsV2` with a delimiter during in-progress multipart uploads,
     * >   the `CommonPrefixes` response parameter contains the prefixes that are associated with the in-progress multipart
     * >   uploads. For more information about multipart uploads, see Multipart Upload Overview [^1] in the *Amazon S3 User
     * >   Guide*.
     * >
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/dev/mpuoverview.html
     *
     * @var string|null
     */
    private $delimiter;

    /**
     * Encoding type used by Amazon S3 to encode the object keys [^1] in the response. Responses are encoded only in UTF-8.
     * An object key can contain any Unicode character. However, the XML 1.0 parser can't parse certain characters, such as
     * characters with an ASCII value from 0 to 10. For characters that aren't supported in XML 1.0, you can add this
     * parameter to request that Amazon S3 encode the keys in the response. For more information about characters to avoid
     * in object key names, see Object key naming guidelines [^2].
     *
     * > When using the URL encoding type, non-ASCII characters that are used in an object's key name will be
     * > percent-encoded according to UTF-8 code values. For example, the object `test_file(3).png` will appear as
     * > `test_file%283%29.png`.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/object-keys.html
     * [^2]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/object-keys.html#object-key-guidelines
     *
     * @var EncodingType::*|null
     */
    private $encodingType;

    /**
     * Sets the maximum number of keys returned in the response. By default, the action returns up to 1,000 key names. The
     * response might contain fewer keys but will never contain more.
     *
     * @var int|null
     */
    private $maxKeys;

    /**
     * Limits the response to keys that begin with the specified prefix.
     *
     * > **Directory buckets** - For directory buckets, only prefixes that end in a delimiter (`/`) are supported.
     *
     * @var string|null
     */
    private $prefix;

    /**
     * `ContinuationToken` indicates to Amazon S3 that the list is being continued on this bucket with a token.
     * `ContinuationToken` is obfuscated and is not a real key. You can use this `ContinuationToken` for pagination of the
     * list results.
     *
     * @var string|null
     */
    private $continuationToken;

    /**
     * The owner field is not present in `ListObjectsV2` by default. If you want to return the owner field with each key in
     * the result, then set the `FetchOwner` field to `true`.
     *
     * > **Directory buckets** - For directory buckets, the bucket owner is returned as the object owner for all objects.
     *
     * @var bool|null
     */
    private $fetchOwner;

    /**
     * StartAfter is where you want Amazon S3 to start listing from. Amazon S3 starts listing after this specified key.
     * StartAfter can be any key in the bucket.
     *
     * > This functionality is not supported for directory buckets.
     *
     * @var string|null
     */
    private $startAfter;

    /**
     * Confirms that the requester knows that she or he will be charged for the list objects request in V2 style. Bucket
     * owners need not specify this parameter in their requests.
     *
     * > This functionality is not supported for directory buckets.
     *
     * @var RequestPayer::*|null
     */
    private $requestPayer;

    /**
     * The account ID of the expected bucket owner. If the account ID that you provide does not match the actual owner of
     * the bucket, the request fails with the HTTP status code `403 Forbidden` (access denied).
     *
     * @var string|null
     */
    private $expectedBucketOwner;

    /**
     * Specifies the optional fields that you want returned in the response. Fields that you do not specify are not
     * returned.
     *
     * > This functionality is not supported for directory buckets.
     *
     * @var list<OptionalObjectAttributes::*>|null
     */
    private $optionalObjectAttributes;

    /**
     * @param array{
     *   Bucket?: string,
     *   Delimiter?: null|string,
     *   EncodingType?: null|EncodingType::*,
     *   MaxKeys?: null|int,
     *   Prefix?: null|string,
     *   ContinuationToken?: null|string,
     *   FetchOwner?: null|bool,
     *   StartAfter?: null|string,
     *   RequestPayer?: null|RequestPayer::*,
     *   ExpectedBucketOwner?: null|string,
     *   OptionalObjectAttributes?: null|array<OptionalObjectAttributes::*>,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->bucket = $input['Bucket'] ?? null;
        $this->delimiter = $input['Delimiter'] ?? null;
        $this->encodingType = $input['EncodingType'] ?? null;
        $this->maxKeys = $input['MaxKeys'] ?? null;
        $this->prefix = $input['Prefix'] ?? null;
        $this->continuationToken = $input['ContinuationToken'] ?? null;
        $this->fetchOwner = $input['FetchOwner'] ?? null;
        $this->startAfter = $input['StartAfter'] ?? null;
        $this->requestPayer = $input['RequestPayer'] ?? null;
        $this->expectedBucketOwner = $input['ExpectedBucketOwner'] ?? null;
        $this->optionalObjectAttributes = $input['OptionalObjectAttributes'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   Bucket?: string,
     *   Delimiter?: null|string,
     *   EncodingType?: null|EncodingType::*,
     *   MaxKeys?: null|int,
     *   Prefix?: null|string,
     *   ContinuationToken?: null|string,
     *   FetchOwner?: null|bool,
     *   StartAfter?: null|string,
     *   RequestPayer?: null|RequestPayer::*,
     *   ExpectedBucketOwner?: null|string,
     *   OptionalObjectAttributes?: null|array<OptionalObjectAttributes::*>,
     *   '@region'?: string|null,
     * }|ListObjectsV2Request $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getBucket(): ?string
    {
        return $this->bucket;
    }

    public function getContinuationToken(): ?string
    {
        return $this->continuationToken;
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

    public function getFetchOwner(): ?bool
    {
        return $this->fetchOwner;
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

    public function getStartAfter(): ?string
    {
        return $this->startAfter;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = ['content-type' => 'application/xml'];
        if (null !== $this->requestPayer) {
            if (!RequestPayer::exists($this->requestPayer)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "RequestPayer" for "%s". The value "%s" is not a valid "RequestPayer".', __CLASS__, $this->requestPayer));
            }
            $headers['x-amz-request-payer'] = $this->requestPayer;
        }
        if (null !== $this->expectedBucketOwner) {
            $headers['x-amz-expected-bucket-owner'] = $this->expectedBucketOwner;
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
        if (null !== $this->maxKeys) {
            $query['max-keys'] = (string) $this->maxKeys;
        }
        if (null !== $this->prefix) {
            $query['prefix'] = $this->prefix;
        }
        if (null !== $this->continuationToken) {
            $query['continuation-token'] = $this->continuationToken;
        }
        if (null !== $this->fetchOwner) {
            $query['fetch-owner'] = $this->fetchOwner ? 'true' : 'false';
        }
        if (null !== $this->startAfter) {
            $query['start-after'] = $this->startAfter;
        }

        // Prepare URI
        $uri = [];
        if (null === $v = $this->bucket) {
            throw new InvalidArgument(\sprintf('Missing parameter "Bucket" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['Bucket'] = $v;
        $uriString = '/' . rawurlencode($uri['Bucket']) . '?list-type=2';

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

    public function setContinuationToken(?string $value): self
    {
        $this->continuationToken = $value;

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

    public function setFetchOwner(?bool $value): self
    {
        $this->fetchOwner = $value;

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

    public function setStartAfter(?string $value): self
    {
        $this->startAfter = $value;

        return $this;
    }
}
