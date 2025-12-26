<?php

namespace AsyncAws\S3Vectors\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Summary information about a vector index.
 */
final class IndexSummary
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
     * @param array{
     *   vectorBucketName: string,
     *   indexName: string,
     *   indexArn: string,
     *   creationTime: \DateTimeImmutable,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->vectorBucketName = $input['vectorBucketName'] ?? $this->throwException(new InvalidArgument('Missing required field "vectorBucketName".'));
        $this->indexName = $input['indexName'] ?? $this->throwException(new InvalidArgument('Missing required field "indexName".'));
        $this->indexArn = $input['indexArn'] ?? $this->throwException(new InvalidArgument('Missing required field "indexArn".'));
        $this->creationTime = $input['creationTime'] ?? $this->throwException(new InvalidArgument('Missing required field "creationTime".'));
    }

    /**
     * @param array{
     *   vectorBucketName: string,
     *   indexName: string,
     *   indexArn: string,
     *   creationTime: \DateTimeImmutable,
     * }|IndexSummary $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCreationTime(): \DateTimeImmutable
    {
        return $this->creationTime;
    }

    public function getIndexArn(): string
    {
        return $this->indexArn;
    }

    public function getIndexName(): string
    {
        return $this->indexName;
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
