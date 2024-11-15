<?php

namespace AsyncAws\S3\Input;

use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class ListBucketsRequest extends Input
{
    /**
     * Maximum number of buckets to be returned in response. When the number is more than the count of buckets that are
     * owned by an Amazon Web Services account, return all the buckets in response.
     *
     * @var int|null
     */
    private $maxBuckets;

    /**
     * `ContinuationToken` indicates to Amazon S3 that the list is being continued on this bucket with a token.
     * `ContinuationToken` is obfuscated and is not a real key. You can use this `ContinuationToken` for pagination of the
     * list results.
     *
     * Length Constraints: Minimum length of 0. Maximum length of 1024.
     *
     * Required: No.
     *
     * > If you specify the `bucket-region`, `prefix`, or `continuation-token` query parameters without using `max-buckets`
     * > to set the maximum number of buckets returned in the response, Amazon S3 applies a default page size of 10,000 and
     * > provides a continuation token if there are more buckets.
     *
     * @var string|null
     */
    private $continuationToken;

    /**
     * Limits the response to bucket names that begin with the specified bucket name prefix.
     *
     * @var string|null
     */
    private $prefix;

    /**
     * Limits the response to buckets that are located in the specified Amazon Web Services Region. The Amazon Web Services
     * Region must be expressed according to the Amazon Web Services Region code, such as `us-west-2` for the US West
     * (Oregon) Region. For a list of the valid values for all of the Amazon Web Services Regions, see Regions and Endpoints
     * [^1].
     *
     * > Requests made to a Regional endpoint that is different from the `bucket-region` parameter are not supported. For
     * > example, if you want to limit the response to your buckets in Region `us-west-2`, the request must be made to an
     * > endpoint in Region `us-west-2`.
     *
     * [^1]: https://docs.aws.amazon.com/general/latest/gr/rande.html#s3_region
     *
     * @var string|null
     */
    private $bucketRegion;

    /**
     * @param array{
     *   MaxBuckets?: null|int,
     *   ContinuationToken?: null|string,
     *   Prefix?: null|string,
     *   BucketRegion?: null|string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->maxBuckets = $input['MaxBuckets'] ?? null;
        $this->continuationToken = $input['ContinuationToken'] ?? null;
        $this->prefix = $input['Prefix'] ?? null;
        $this->bucketRegion = $input['BucketRegion'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   MaxBuckets?: null|int,
     *   ContinuationToken?: null|string,
     *   Prefix?: null|string,
     *   BucketRegion?: null|string,
     *   '@region'?: string|null,
     * }|ListBucketsRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getBucketRegion(): ?string
    {
        return $this->bucketRegion;
    }

    public function getContinuationToken(): ?string
    {
        return $this->continuationToken;
    }

    public function getMaxBuckets(): ?int
    {
        return $this->maxBuckets;
    }

    public function getPrefix(): ?string
    {
        return $this->prefix;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = ['content-type' => 'application/xml'];

        // Prepare query
        $query = [];
        if (null !== $this->maxBuckets) {
            $query['max-buckets'] = (string) $this->maxBuckets;
        }
        if (null !== $this->continuationToken) {
            $query['continuation-token'] = $this->continuationToken;
        }
        if (null !== $this->prefix) {
            $query['prefix'] = $this->prefix;
        }
        if (null !== $this->bucketRegion) {
            $query['bucket-region'] = $this->bucketRegion;
        }

        // Prepare URI
        $uriString = '/';

        // Prepare Body
        $body = '';

        // Return the Request
        return new Request('GET', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setBucketRegion(?string $value): self
    {
        $this->bucketRegion = $value;

        return $this;
    }

    public function setContinuationToken(?string $value): self
    {
        $this->continuationToken = $value;

        return $this;
    }

    public function setMaxBuckets(?int $value): self
    {
        $this->maxBuckets = $value;

        return $this;
    }

    public function setPrefix(?string $value): self
    {
        $this->prefix = $value;

        return $this;
    }
}
