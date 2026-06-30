<?php

namespace AsyncAws\Lambda\ValueObject;

/**
 * Details about the resolved Amazon S3 object that contains a function's deployment package.
 */
final class ResolvedS3Object
{
    /**
     * The Amazon S3 bucket that contains the deployment package.
     *
     * @var string|null
     */
    private $s3Bucket;

    /**
     * The Amazon S3 key of the deployment package.
     *
     * @var string|null
     */
    private $s3Key;

    /**
     * The version of the deployment package object.
     *
     * @var string|null
     */
    private $s3ObjectVersion;

    /**
     * @param array{
     *   S3Bucket?: string|null,
     *   S3Key?: string|null,
     *   S3ObjectVersion?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->s3Bucket = $input['S3Bucket'] ?? null;
        $this->s3Key = $input['S3Key'] ?? null;
        $this->s3ObjectVersion = $input['S3ObjectVersion'] ?? null;
    }

    /**
     * @param array{
     *   S3Bucket?: string|null,
     *   S3Key?: string|null,
     *   S3ObjectVersion?: string|null,
     * }|ResolvedS3Object $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getS3Bucket(): ?string
    {
        return $this->s3Bucket;
    }

    public function getS3Key(): ?string
    {
        return $this->s3Key;
    }

    public function getS3ObjectVersion(): ?string
    {
        return $this->s3ObjectVersion;
    }
}
