<?php

namespace AsyncAws\S3Vectors\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * The attributes of a vector bucket.
 */
final class VectorBucket
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
     * The encryption configuration for the vector bucket.
     *
     * @var EncryptionConfiguration|null
     */
    private $encryptionConfiguration;

    /**
     * @param array{
     *   vectorBucketName: string,
     *   vectorBucketArn: string,
     *   creationTime: \DateTimeImmutable,
     *   encryptionConfiguration?: EncryptionConfiguration|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->vectorBucketName = $input['vectorBucketName'] ?? $this->throwException(new InvalidArgument('Missing required field "vectorBucketName".'));
        $this->vectorBucketArn = $input['vectorBucketArn'] ?? $this->throwException(new InvalidArgument('Missing required field "vectorBucketArn".'));
        $this->creationTime = $input['creationTime'] ?? $this->throwException(new InvalidArgument('Missing required field "creationTime".'));
        $this->encryptionConfiguration = isset($input['encryptionConfiguration']) ? EncryptionConfiguration::create($input['encryptionConfiguration']) : null;
    }

    /**
     * @param array{
     *   vectorBucketName: string,
     *   vectorBucketArn: string,
     *   creationTime: \DateTimeImmutable,
     *   encryptionConfiguration?: EncryptionConfiguration|array|null,
     * }|VectorBucket $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCreationTime(): \DateTimeImmutable
    {
        return $this->creationTime;
    }

    public function getEncryptionConfiguration(): ?EncryptionConfiguration
    {
        return $this->encryptionConfiguration;
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
