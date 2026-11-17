<?php

namespace AsyncAws\S3Vectors\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\S3Vectors\Enum\IndexMode;
use AsyncAws\S3Vectors\ValueObject\QueryText;
use AsyncAws\S3Vectors\ValueObject\VectorData;

final class QueryVectorsInput extends Input
{
    /**
     * The name of the vector bucket that contains the vector index.
     *
     * @var string|null
     */
    private $vectorBucketName;

    /**
     * The name of the vector index that you want to query.
     *
     * @var string|null
     */
    private $indexName;

    /**
     * The ARN of the vector index that you want to query.
     *
     * @var string|null
     */
    private $indexArn;

    /**
     * The number of results to return for each query.
     *
     * @required
     *
     * @var int|null
     */
    private $topK;

    /**
     * The query vector for vector similarity search. The query vector must have the same dimension as the vector index. For
     * example, if the vector index has 384 dimensions, the query vector must also have 384 dimensions. You can omit this
     * parameter when you supply `queryTexts` to perform a full-text-only search. A request must include `queryVector`,
     * `queryTexts`, or both.
     *
     * @var VectorData|null
     */
    private $queryVector;

    /**
     * Metadata filter to apply during the query. For more information about metadata keys, see Metadata filtering [^1] in
     * the *Amazon S3 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/s3-vectors-metadata-filtering.html
     *
     * @var bool|string|int|float|list<mixed>|array<string, mixed>|null
     */
    private $filter;

    /**
     * The mode to use to process the query. If you don't specify a query mode, the operation uses the mode that's currently
     * configured for the vector index.
     *
     * Valid values:
     *
     * - `CLASSIC` - Applies metadata filters during the vector search. You can't specify `CLASSIC` for an `ENHANCED` index.
     * - `ENHANCED` - Applies metadata filters before the vector search.
     *
     * @var IndexMode::*|null
     */
    private $queryMode;

    /**
     * Indicates whether to include metadata in the response. The default value is `false`.
     *
     * @var bool|null
     */
    private $returnMetadata;

    /**
     * Indicates whether to include the computed distance in the response. The default value is `false`.
     *
     * @var bool|null
     */
    private $returnDistance;

    /**
     * Full-text queries for searchable metadata keys. Omit `queryVector` to run a full-text-only search, or provide both
     * fields to run a hybrid search. Each entry must target a distinct searchable metadata key.
     *
     * @var QueryText[]|null
     */
    private $queryTexts;

    /**
     * Indicates whether to include relevance scores in the response. Full-text-only results can include BM25 scores. Hybrid
     * results can also include a Reciprocal Rank Fusion (RRF) score and vector distance. This setting doesn't add scores to
     * vector-only requests. The default value is `false`.
     *
     * @var bool|null
     */
    private $returnScores;

    /**
     * Pagination token from a previous request. The value of this field is empty for an initial request.
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * @param array{
     *   vectorBucketName?: string|null,
     *   indexName?: string|null,
     *   indexArn?: string|null,
     *   topK?: int,
     *   queryVector?: VectorData|array|null,
     *   filter?: bool|string|int|float|list<mixed>|array<string, mixed>|null|null,
     *   queryMode?: IndexMode::*|null,
     *   returnMetadata?: bool|null,
     *   returnDistance?: bool|null,
     *   queryTexts?: array<QueryText|array>|null,
     *   returnScores?: bool|null,
     *   nextToken?: string|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->vectorBucketName = $input['vectorBucketName'] ?? null;
        $this->indexName = $input['indexName'] ?? null;
        $this->indexArn = $input['indexArn'] ?? null;
        $this->topK = $input['topK'] ?? null;
        $this->queryVector = isset($input['queryVector']) ? VectorData::create($input['queryVector']) : null;
        $this->filter = $input['filter'] ?? null;
        $this->queryMode = $input['queryMode'] ?? null;
        $this->returnMetadata = $input['returnMetadata'] ?? null;
        $this->returnDistance = $input['returnDistance'] ?? null;
        $this->queryTexts = isset($input['queryTexts']) ? array_map([QueryText::class, 'create'], $input['queryTexts']) : null;
        $this->returnScores = $input['returnScores'] ?? null;
        $this->nextToken = $input['nextToken'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   vectorBucketName?: string|null,
     *   indexName?: string|null,
     *   indexArn?: string|null,
     *   topK?: int,
     *   queryVector?: VectorData|array|null,
     *   filter?: bool|string|int|float|list<mixed>|array<string, mixed>|null|null,
     *   queryMode?: IndexMode::*|null,
     *   returnMetadata?: bool|null,
     *   returnDistance?: bool|null,
     *   queryTexts?: array<QueryText|array>|null,
     *   returnScores?: bool|null,
     *   nextToken?: string|null,
     *   '@region'?: string|null,
     * }|QueryVectorsInput $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return bool|string|int|float|list<mixed>|array<string, mixed>|null
     */
    public function getFilter(): bool|string|int|float|array|null
    {
        return $this->filter;
    }

    public function getIndexArn(): ?string
    {
        return $this->indexArn;
    }

    public function getIndexName(): ?string
    {
        return $this->indexName;
    }

    public function getNextToken(): ?string
    {
        return $this->nextToken;
    }

    /**
     * @return IndexMode::*|null
     */
    public function getQueryMode(): ?string
    {
        return $this->queryMode;
    }

    /**
     * @return QueryText[]
     */
    public function getQueryTexts(): array
    {
        return $this->queryTexts ?? [];
    }

    public function getQueryVector(): ?VectorData
    {
        return $this->queryVector;
    }

    public function getReturnDistance(): ?bool
    {
        return $this->returnDistance;
    }

    public function getReturnMetadata(): ?bool
    {
        return $this->returnMetadata;
    }

    public function getReturnScores(): ?bool
    {
        return $this->returnScores;
    }

    public function getTopK(): ?int
    {
        return $this->topK;
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
        $uriString = '/QueryVectors';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    /**
     * @param bool|string|int|float|list<mixed>|array<string, mixed>|null $value
     */
    public function setFilter(bool|string|int|float|array|null $value): self
    {
        $this->filter = $value;

        return $this;
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

    public function setNextToken(?string $value): self
    {
        $this->nextToken = $value;

        return $this;
    }

    /**
     * @param IndexMode::*|null $value
     */
    public function setQueryMode(?string $value): self
    {
        $this->queryMode = $value;

        return $this;
    }

    /**
     * @param QueryText[] $value
     */
    public function setQueryTexts(array $value): self
    {
        $this->queryTexts = $value;

        return $this;
    }

    public function setQueryVector(?VectorData $value): self
    {
        $this->queryVector = $value;

        return $this;
    }

    public function setReturnDistance(?bool $value): self
    {
        $this->returnDistance = $value;

        return $this;
    }

    public function setReturnMetadata(?bool $value): self
    {
        $this->returnMetadata = $value;

        return $this;
    }

    public function setReturnScores(?bool $value): self
    {
        $this->returnScores = $value;

        return $this;
    }

    public function setTopK(?int $value): self
    {
        $this->topK = $value;

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
        if (null === $v = $this->topK) {
            throw new InvalidArgument(\sprintf('Missing parameter "topK" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['topK'] = $v;
        if (null !== $v = $this->queryVector) {
            $payload['queryVector'] = $v->requestBody();
        }
        if (null !== $v = $this->filter) {
            $payload['filter'] = $v;
        }
        if (null !== $v = $this->queryMode) {
            if (!IndexMode::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "queryMode" for "%s". The value "%s" is not a valid "IndexMode".', __CLASS__, $v));
            }
            $payload['queryMode'] = $v;
        }
        if (null !== $v = $this->returnMetadata) {
            $payload['returnMetadata'] = (bool) $v;
        }
        if (null !== $v = $this->returnDistance) {
            $payload['returnDistance'] = (bool) $v;
        }
        if (null !== $v = $this->queryTexts) {
            $index = -1;
            $payload['queryTexts'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['queryTexts'][$index] = $listValue->requestBody();
            }
        }
        if (null !== $v = $this->returnScores) {
            $payload['returnScores'] = (bool) $v;
        }
        if (null !== $v = $this->nextToken) {
            $payload['nextToken'] = $v;
        }

        return $payload;
    }
}
