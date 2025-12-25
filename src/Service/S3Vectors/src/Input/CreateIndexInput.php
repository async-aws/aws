<?php

namespace AsyncAws\S3Vectors\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\S3Vectors\Enum\DataType;
use AsyncAws\S3Vectors\Enum\DistanceMetric;
use AsyncAws\S3Vectors\ValueObject\EncryptionConfiguration;
use AsyncAws\S3Vectors\ValueObject\MetadataConfiguration;

final class CreateIndexInput extends Input
{
    /**
     * The name of the vector bucket to create the vector index in.
     *
     * @var string|null
     */
    private $vectorBucketName;

    /**
     * The Amazon Resource Name (ARN) of the vector bucket to create the vector index in.
     *
     * @var string|null
     */
    private $vectorBucketArn;

    /**
     * The name of the vector index to create.
     *
     * @required
     *
     * @var string|null
     */
    private $indexName;

    /**
     * The data type of the vectors to be inserted into the vector index.
     *
     * @required
     *
     * @var DataType::*|null
     */
    private $dataType;

    /**
     * The dimensions of the vectors to be inserted into the vector index.
     *
     * @required
     *
     * @var int|null
     */
    private $dimension;

    /**
     * The distance metric to be used for similarity search.
     *
     * @required
     *
     * @var DistanceMetric::*|null
     */
    private $distanceMetric;

    /**
     * The metadata configuration for the vector index.
     *
     * @var MetadataConfiguration|null
     */
    private $metadataConfiguration;

    /**
     * The encryption configuration for a vector index. By default, if you don't specify, all new vectors in the vector
     * index will use the encryption configuration of the vector bucket.
     *
     * @var EncryptionConfiguration|null
     */
    private $encryptionConfiguration;

    /**
     * An array of user-defined tags that you would like to apply to the vector index that you are creating. A tag is a
     * key-value pair that you apply to your resources. Tags can help you organize, track costs, and control access to
     * resources. For more information, see Tagging for cost allocation or attribute-based access control (ABAC) [^1].
     *
     * > You must have the `s3vectors:TagResource` permission in addition to `s3vectors:CreateIndex` permission to create a
     * > vector index with tags.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/tagging.html
     *
     * @var array<string, string>|null
     */
    private $tags;

    /**
     * @param array{
     *   vectorBucketName?: string|null,
     *   vectorBucketArn?: string|null,
     *   indexName?: string,
     *   dataType?: DataType::*,
     *   dimension?: int,
     *   distanceMetric?: DistanceMetric::*,
     *   metadataConfiguration?: MetadataConfiguration|array|null,
     *   encryptionConfiguration?: EncryptionConfiguration|array|null,
     *   tags?: array<string, string>|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->vectorBucketName = $input['vectorBucketName'] ?? null;
        $this->vectorBucketArn = $input['vectorBucketArn'] ?? null;
        $this->indexName = $input['indexName'] ?? null;
        $this->dataType = $input['dataType'] ?? null;
        $this->dimension = $input['dimension'] ?? null;
        $this->distanceMetric = $input['distanceMetric'] ?? null;
        $this->metadataConfiguration = isset($input['metadataConfiguration']) ? MetadataConfiguration::create($input['metadataConfiguration']) : null;
        $this->encryptionConfiguration = isset($input['encryptionConfiguration']) ? EncryptionConfiguration::create($input['encryptionConfiguration']) : null;
        $this->tags = $input['tags'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   vectorBucketName?: string|null,
     *   vectorBucketArn?: string|null,
     *   indexName?: string,
     *   dataType?: DataType::*,
     *   dimension?: int,
     *   distanceMetric?: DistanceMetric::*,
     *   metadataConfiguration?: MetadataConfiguration|array|null,
     *   encryptionConfiguration?: EncryptionConfiguration|array|null,
     *   tags?: array<string, string>|null,
     *   '@region'?: string|null,
     * }|CreateIndexInput $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return DataType::*|null
     */
    public function getDataType(): ?string
    {
        return $this->dataType;
    }

    public function getDimension(): ?int
    {
        return $this->dimension;
    }

    /**
     * @return DistanceMetric::*|null
     */
    public function getDistanceMetric(): ?string
    {
        return $this->distanceMetric;
    }

    public function getEncryptionConfiguration(): ?EncryptionConfiguration
    {
        return $this->encryptionConfiguration;
    }

    public function getIndexName(): ?string
    {
        return $this->indexName;
    }

    public function getMetadataConfiguration(): ?MetadataConfiguration
    {
        return $this->metadataConfiguration;
    }

    /**
     * @return array<string, string>
     */
    public function getTags(): array
    {
        return $this->tags ?? [];
    }

    public function getVectorBucketArn(): ?string
    {
        return $this->vectorBucketArn;
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
        $uriString = '/CreateIndex';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    /**
     * @param DataType::*|null $value
     */
    public function setDataType(?string $value): self
    {
        $this->dataType = $value;

        return $this;
    }

    public function setDimension(?int $value): self
    {
        $this->dimension = $value;

        return $this;
    }

    /**
     * @param DistanceMetric::*|null $value
     */
    public function setDistanceMetric(?string $value): self
    {
        $this->distanceMetric = $value;

        return $this;
    }

    public function setEncryptionConfiguration(?EncryptionConfiguration $value): self
    {
        $this->encryptionConfiguration = $value;

        return $this;
    }

    public function setIndexName(?string $value): self
    {
        $this->indexName = $value;

        return $this;
    }

    public function setMetadataConfiguration(?MetadataConfiguration $value): self
    {
        $this->metadataConfiguration = $value;

        return $this;
    }

    /**
     * @param array<string, string> $value
     */
    public function setTags(array $value): self
    {
        $this->tags = $value;

        return $this;
    }

    public function setVectorBucketArn(?string $value): self
    {
        $this->vectorBucketArn = $value;

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
        if (null !== $v = $this->vectorBucketArn) {
            $payload['vectorBucketArn'] = $v;
        }
        if (null === $v = $this->indexName) {
            throw new InvalidArgument(\sprintf('Missing parameter "indexName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['indexName'] = $v;
        if (null === $v = $this->dataType) {
            throw new InvalidArgument(\sprintf('Missing parameter "dataType" for "%s". The value cannot be null.', __CLASS__));
        }
        if (!DataType::exists($v)) {
            throw new InvalidArgument(\sprintf('Invalid parameter "dataType" for "%s". The value "%s" is not a valid "DataType".', __CLASS__, $v));
        }
        $payload['dataType'] = $v;
        if (null === $v = $this->dimension) {
            throw new InvalidArgument(\sprintf('Missing parameter "dimension" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['dimension'] = $v;
        if (null === $v = $this->distanceMetric) {
            throw new InvalidArgument(\sprintf('Missing parameter "distanceMetric" for "%s". The value cannot be null.', __CLASS__));
        }
        if (!DistanceMetric::exists($v)) {
            throw new InvalidArgument(\sprintf('Invalid parameter "distanceMetric" for "%s". The value "%s" is not a valid "DistanceMetric".', __CLASS__, $v));
        }
        $payload['distanceMetric'] = $v;
        if (null !== $v = $this->metadataConfiguration) {
            $payload['metadataConfiguration'] = $v->requestBody();
        }
        if (null !== $v = $this->encryptionConfiguration) {
            $payload['encryptionConfiguration'] = $v->requestBody();
        }
        if (null !== $v = $this->tags) {
            if (empty($v)) {
                $payload['tags'] = new \stdClass();
            } else {
                $payload['tags'] = [];
                foreach ($v as $name => $mv) {
                    $payload['tags'][$name] = $mv;
                }
            }
        }

        return $payload;
    }
}
