<?php

namespace AsyncAws\S3Vectors\Input;

use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class ListVectorsInput extends Input
{
    /**
     * The name of the vector bucket.
     *
     * @var string|null
     */
    private $vectorBucketName;

    /**
     * The name of the vector index.
     *
     * @var string|null
     */
    private $indexName;

    /**
     * The Amazon resource Name (ARN) of the vector index.
     *
     * @var string|null
     */
    private $indexArn;

    /**
     * The maximum number of vectors to return on a page.
     *
     * If you don't specify `maxResults`, the `ListVectors` operation uses a default value of 500.
     *
     * If the processed dataset size exceeds 1 MB before reaching the `maxResults` value, the operation stops and returns
     * the vectors that are retrieved up to that point, along with a `nextToken` that you can use in a subsequent request to
     * retrieve the next set of results.
     *
     * @var int|null
     */
    private $maxResults;

    /**
     * Pagination token from a previous request. The value of this field is empty for an initial request.
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * For a parallel `ListVectors` request, `segmentCount` represents the total number of vector segments into which the
     * `ListVectors` operation will be divided. The value of `segmentCount` corresponds to the number of application workers
     * that will perform the parallel `ListVectors` operation. For example, if you want to use four application threads to
     * list vectors in a vector index, specify a `segmentCount` value of 4.
     *
     * If you specify a `segmentCount` value of 1, the `ListVectors` operation will be sequential rather than parallel.
     *
     * If you specify `segmentCount`, you must also specify `segmentIndex`.
     *
     * @var int|null
     */
    private $segmentCount;

    /**
     * For a parallel `ListVectors` request, `segmentIndex` is the index of the segment from which to list vectors in the
     * current request. It identifies an individual segment to be listed by an application worker.
     *
     * Segment IDs are zero-based, so the first segment is always 0. For example, if you want to use four application
     * threads to list vectors in a vector index, then the first thread specifies a `segmentIndex` value of 0, the second
     * thread specifies 1, and so on.
     *
     * The value of `segmentIndex` must be less than the value provided for `segmentCount`.
     *
     * If you provide `segmentIndex`, you must also provide `segmentCount`.
     *
     * @var int|null
     */
    private $segmentIndex;

    /**
     * If true, the vector data of each vector will be included in the response. The default value is `false`.
     *
     * @var bool|null
     */
    private $returnData;

    /**
     * If true, the metadata associated with each vector will be included in the response. The default value is `false`.
     *
     * @var bool|null
     */
    private $returnMetadata;

    /**
     * @param array{
     *   vectorBucketName?: string|null,
     *   indexName?: string|null,
     *   indexArn?: string|null,
     *   maxResults?: int|null,
     *   nextToken?: string|null,
     *   segmentCount?: int|null,
     *   segmentIndex?: int|null,
     *   returnData?: bool|null,
     *   returnMetadata?: bool|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->vectorBucketName = $input['vectorBucketName'] ?? null;
        $this->indexName = $input['indexName'] ?? null;
        $this->indexArn = $input['indexArn'] ?? null;
        $this->maxResults = $input['maxResults'] ?? null;
        $this->nextToken = $input['nextToken'] ?? null;
        $this->segmentCount = $input['segmentCount'] ?? null;
        $this->segmentIndex = $input['segmentIndex'] ?? null;
        $this->returnData = $input['returnData'] ?? null;
        $this->returnMetadata = $input['returnMetadata'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   vectorBucketName?: string|null,
     *   indexName?: string|null,
     *   indexArn?: string|null,
     *   maxResults?: int|null,
     *   nextToken?: string|null,
     *   segmentCount?: int|null,
     *   segmentIndex?: int|null,
     *   returnData?: bool|null,
     *   returnMetadata?: bool|null,
     *   '@region'?: string|null,
     * }|ListVectorsInput $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getIndexArn(): ?string
    {
        return $this->indexArn;
    }

    public function getIndexName(): ?string
    {
        return $this->indexName;
    }

    public function getMaxResults(): ?int
    {
        return $this->maxResults;
    }

    public function getNextToken(): ?string
    {
        return $this->nextToken;
    }

    public function getReturnData(): ?bool
    {
        return $this->returnData;
    }

    public function getReturnMetadata(): ?bool
    {
        return $this->returnMetadata;
    }

    public function getSegmentCount(): ?int
    {
        return $this->segmentCount;
    }

    public function getSegmentIndex(): ?int
    {
        return $this->segmentIndex;
    }

    public function getVectorBucketName(): ?string
    {
        return $this->vectorBucketName;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/ListVectors';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setIndexArn(?string $value): self
    {
        $this->indexArn = $value;

        return $this;
    }

    public function setIndexName(?string $value): self
    {
        $this->indexName = $value;

        return $this;
    }

    public function setMaxResults(?int $value): self
    {
        $this->maxResults = $value;

        return $this;
    }

    public function setNextToken(?string $value): self
    {
        $this->nextToken = $value;

        return $this;
    }

    public function setReturnData(?bool $value): self
    {
        $this->returnData = $value;

        return $this;
    }

    public function setReturnMetadata(?bool $value): self
    {
        $this->returnMetadata = $value;

        return $this;
    }

    public function setSegmentCount(?int $value): self
    {
        $this->segmentCount = $value;

        return $this;
    }

    public function setSegmentIndex(?int $value): self
    {
        $this->segmentIndex = $value;

        return $this;
    }

    public function setVectorBucketName(?string $value): self
    {
        $this->vectorBucketName = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->vectorBucketName) {
            $payload['vectorBucketName'] = $v;
        }
        if (null !== $v = $this->indexName) {
            $payload['indexName'] = $v;
        }
        if (null !== $v = $this->indexArn) {
            $payload['indexArn'] = $v;
        }
        if (null !== $v = $this->maxResults) {
            $payload['maxResults'] = $v;
        }
        if (null !== $v = $this->nextToken) {
            $payload['nextToken'] = $v;
        }
        if (null !== $v = $this->segmentCount) {
            $payload['segmentCount'] = $v;
        }
        if (null !== $v = $this->segmentIndex) {
            $payload['segmentIndex'] = $v;
        }
        if (null !== $v = $this->returnData) {
            $payload['returnData'] = (bool) $v;
        }
        if (null !== $v = $this->returnMetadata) {
            $payload['returnMetadata'] = (bool) $v;
        }

        return $payload;
    }
}
