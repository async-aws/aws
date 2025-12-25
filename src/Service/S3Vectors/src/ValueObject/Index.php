<?php

namespace AsyncAws\S3Vectors\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\S3Vectors\Enum\DataType;
use AsyncAws\S3Vectors\Enum\DistanceMetric;

/**
 * The attributes of a vector index.
 */
final class Index
{
    /**
     * The name of the vector bucket that contains the vector index.
     *
     * @var string
     */
    private $vectorBucketName;

    /**
     * The name of the vector index.
     *
     * @var string
     */
    private $indexName;

    /**
     * The Amazon Resource Name (ARN) of the vector index.
     *
     * @var string
     */
    private $indexArn;

    /**
     * Date and time when the vector index was created.
     *
     * @var \DateTimeImmutable
     */
    private $creationTime;

    /**
     * The data type of the vectors inserted into the vector index.
     *
     * @var DataType::*
     */
    private $dataType;

    /**
     * The number of values in the vectors that are inserted into the vector index.
     *
     * @var int
     */
    private $dimension;

    /**
     * The distance metric to be used for similarity search.
     *
     * @var DistanceMetric::*
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
     * @param array{
     *   vectorBucketName: string,
     *   indexName: string,
     *   indexArn: string,
     *   creationTime: \DateTimeImmutable,
     *   dataType: DataType::*,
     *   dimension: int,
     *   distanceMetric: DistanceMetric::*,
     *   metadataConfiguration?: MetadataConfiguration|array|null,
     *   encryptionConfiguration?: EncryptionConfiguration|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->vectorBucketName = $input['vectorBucketName'] ?? $this->throwException(new InvalidArgument('Missing required field "vectorBucketName".'));
        $this->indexName = $input['indexName'] ?? $this->throwException(new InvalidArgument('Missing required field "indexName".'));
        $this->indexArn = $input['indexArn'] ?? $this->throwException(new InvalidArgument('Missing required field "indexArn".'));
        $this->creationTime = $input['creationTime'] ?? $this->throwException(new InvalidArgument('Missing required field "creationTime".'));
        $this->dataType = $input['dataType'] ?? $this->throwException(new InvalidArgument('Missing required field "dataType".'));
        $this->dimension = $input['dimension'] ?? $this->throwException(new InvalidArgument('Missing required field "dimension".'));
        $this->distanceMetric = $input['distanceMetric'] ?? $this->throwException(new InvalidArgument('Missing required field "distanceMetric".'));
        $this->metadataConfiguration = isset($input['metadataConfiguration']) ? MetadataConfiguration::create($input['metadataConfiguration']) : null;
        $this->encryptionConfiguration = isset($input['encryptionConfiguration']) ? EncryptionConfiguration::create($input['encryptionConfiguration']) : null;
    }

    /**
     * @param array{
     *   vectorBucketName: string,
     *   indexName: string,
     *   indexArn: string,
     *   creationTime: \DateTimeImmutable,
     *   dataType: DataType::*,
     *   dimension: int,
     *   distanceMetric: DistanceMetric::*,
     *   metadataConfiguration?: MetadataConfiguration|array|null,
     *   encryptionConfiguration?: EncryptionConfiguration|array|null,
     * }|Index $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCreationTime(): \DateTimeImmutable
    {
        return $this->creationTime;
    }

    /**
     * @return DataType::*
     */
    public function getDataType(): string
    {
        return $this->dataType;
    }

    public function getDimension(): int
    {
        return $this->dimension;
    }

    /**
     * @return DistanceMetric::*
     */
    public function getDistanceMetric(): string
    {
        return $this->distanceMetric;
    }

    public function getEncryptionConfiguration(): ?EncryptionConfiguration
    {
        return $this->encryptionConfiguration;
    }

    public function getIndexArn(): string
    {
        return $this->indexArn;
    }

    public function getIndexName(): string
    {
        return $this->indexName;
    }

    public function getMetadataConfiguration(): ?MetadataConfiguration
    {
        return $this->metadataConfiguration;
    }

    public function getVectorBucketName(): string
    {
        return $this->vectorBucketName;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
