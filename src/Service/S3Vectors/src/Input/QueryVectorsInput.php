<?php

namespace AsyncAws\S3Vectors\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
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
     * The query vector. Ensure that the query vector has the same dimension as the dimension of the vector index that's
     * being queried. For example, if your vector index contains vectors with 384 dimensions, your query vector must also
     * have 384 dimensions.
     *
     * @required
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
     * @param array{
     *   vectorBucketName?: string|null,
     *   indexName?: string|null,
     *   indexArn?: string|null,
     *   topK?: int,
     *   queryVector?: VectorData|array,
     *   filter?: bool|string|int|float|list<mixed>|array<string, mixed>|null|null,
     *   returnMetadata?: bool|null,
     *   returnDistance?: bool|null,
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
        $this->returnMetadata = $input['returnMetadata'] ?? null;
        $this->returnDistance = $input['returnDistance'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   vectorBucketName?: string|null,
     *   indexName?: string|null,
     *   indexArn?: string|null,
     *   topK?: int,
     *   queryVector?: VectorData|array,
     *   filter?: bool|string|int|float|list<mixed>|array<string, mixed>|null|null,
     *   returnMetadata?: bool|null,
     *   returnDistance?: bool|null,
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
        if (null === $v = $this->queryVector) {
            throw new InvalidArgument(\sprintf('Missing parameter "queryVector" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['queryVector'] = $v->requestBody();
        if (null !== $v = $this->filter) {
            $payload['filter'] = $v;
        }
        if (null !== $v = $this->returnMetadata) {
            $payload['returnMetadata'] = (bool) $v;
        }
        if (null !== $v = $this->returnDistance) {
            $payload['returnDistance'] = (bool) $v;
        }

        return $payload;
    }
}
