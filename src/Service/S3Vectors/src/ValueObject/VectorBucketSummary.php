<?php

namespace AsyncAws\S3Vectors\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Summary information about a vector bucket.
 */
final class VectorBucketSummary
{
    /**
     * The name of the vector bucket.
     *
     * @var string
     */
    private $vectorBucketName;

    /**
     * The Amazon Resource Name (ARN) of the vector bucket.
     *
     * @var string
     */
    private $vectorBucketArn;

    /**
     * Date and time when the vector bucket was created.
     *
     * @var \DateTimeImmutable
     */
    private $creationTime;

    /**
     * @param array{
     *   vectorBucketName: string,
     *   vectorBucketArn: string,
     *   creationTime: \DateTimeImmutable,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->vectorBucketName = $input['vectorBucketName'] ?? $this->throwException(new InvalidArgument('Missing required field "vectorBucketName".'));
        $this->vectorBucketArn = $input['vectorBucketArn'] ?? $this->throwException(new InvalidArgument('Missing required field "vectorBucketArn".'));
        $this->creationTime = $input['creationTime'] ?? $this->throwException(new InvalidArgument('Missing required field "creationTime".'));
    }

    /**
     * @param array{
     *   vectorBucketName: string,
     *   vectorBucketArn: string,
     *   creationTime: \DateTimeImmutable,
     * }|VectorBucketSummary $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCreationTime(): \DateTimeImmutable
    {
        return $this->creationTime;
    }

    public function getVectorBucketArn(): string
    {
        return $this->vectorBucketArn;
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
