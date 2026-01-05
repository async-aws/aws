<?php

namespace AsyncAws\S3Vectors\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\S3Vectors\ValueObject\PutInputVector;

final class PutVectorsInput extends Input
{
    /**
     * The name of the vector bucket that contains the vector index.
     *
     * @var string|null
     */
    private $vectorBucketName;

    /**
     * The name of the vector index where you want to write vectors.
     *
     * @var string|null
     */
    private $indexName;

    /**
     * The ARN of the vector index where you want to write vectors.
     *
     * @var string|null
     */
    private $indexArn;

    /**
     * The vectors to add to a vector index. The number of vectors in a single request must not exceed the resource
     * capacity, otherwise the request will be rejected with the error `ServiceUnavailableException` with the error message
     * "Currently unable to handle the request".
     *
     * @required
     *
     * @var PutInputVector[]|null
     */
    private $vectors;

    /**
     * @param array{
     *   vectorBucketName?: string|null,
     *   indexName?: string|null,
     *   indexArn?: string|null,
     *   vectors?: array<PutInputVector|array>,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->vectorBucketName = $input['vectorBucketName'] ?? null;
        $this->indexName = $input['indexName'] ?? null;
        $this->indexArn = $input['indexArn'] ?? null;
        $this->vectors = isset($input['vectors']) ? array_map([PutInputVector::class, 'create'], $input['vectors']) : null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   vectorBucketName?: string|null,
     *   indexName?: string|null,
     *   indexArn?: string|null,
     *   vectors?: array<PutInputVector|array>,
     *   '@region'?: string|null,
     * }|PutVectorsInput $input
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

    public function getVectorBucketName(): ?string
    {
        return $this->vectorBucketName;
    }

    /**
     * @return PutInputVector[]
     */
    public function getVectors(): array
    {
        return $this->vectors ?? [];
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
        $uriString = '/PutVectors';

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

    public function setVectorBucketName(?string $value): self
    {
        $this->vectorBucketName = $value;

        return $this;
    }

    /**
     * @param PutInputVector[] $value
     */
    public function setVectors(array $value): self
    {
        $this->vectors = $value;

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
        if (null === $v = $this->vectors) {
            throw new InvalidArgument(\sprintf('Missing parameter "vectors" for "%s". The value cannot be null.', __CLASS__));
        }

        $index = -1;
        $payload['vectors'] = [];
        foreach ($v as $listValue) {
            ++$index;
            $payload['vectors'][$index] = $listValue->requestBody();
        }

        return $payload;
    }
}
