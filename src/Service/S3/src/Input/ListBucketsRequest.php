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
     * @var string|null
     */
    private $continuationToken;

    /**
     * @param array{
     *   MaxBuckets?: null|int,
     *   ContinuationToken?: null|string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->maxBuckets = $input['MaxBuckets'] ?? null;
        $this->continuationToken = $input['ContinuationToken'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   MaxBuckets?: null|int,
     *   ContinuationToken?: null|string,
     *   '@region'?: string|null,
     * }|ListBucketsRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getContinuationToken(): ?string
    {
        return $this->continuationToken;
    }

    public function getMaxBuckets(): ?int
    {
        return $this->maxBuckets;
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

        // Prepare URI
        $uriString = '/';

        // Prepare Body
        $body = '';

        // Return the Request
        return new Request('GET', $uriString, $query, $headers, StreamFactory::create($body));
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
}
