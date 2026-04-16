<?php

namespace AsyncAws\ImageBuilder\ValueObject;

/**
 * Amazon S3 logging configuration.
 */
final class S3Logs
{
    /**
     * The S3 bucket in which to store the logs.
     *
     * @var string|null
     */
    private $s3BucketName;

    /**
     * The Amazon S3 path to the bucket where the logs are stored.
     *
     * @var string|null
     */
    private $s3KeyPrefix;

    /**
     * @param array{
     *   s3BucketName?: string|null,
     *   s3KeyPrefix?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->s3BucketName = $input['s3BucketName'] ?? null;
        $this->s3KeyPrefix = $input['s3KeyPrefix'] ?? null;
    }

    /**
     * @param array{
     *   s3BucketName?: string|null,
     *   s3KeyPrefix?: string|null,
     * }|S3Logs $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getS3BucketName(): ?string
    {
        return $this->s3BucketName;
    }

    public function getS3KeyPrefix(): ?string
    {
        return $this->s3KeyPrefix;
    }
}
