<?php

namespace AsyncAws\Lambda\Input;

class LayerVersionContentInput
{
    /**
     * The Amazon S3 bucket of the layer archive.
     *
     * @var string|null
     */
    private $S3Bucket;

    /**
     * The Amazon S3 key of the layer archive.
     *
     * @var string|null
     */
    private $S3Key;

    /**
     * For versioned objects, the version of the layer archive object to use.
     *
     * @var string|null
     */
    private $S3ObjectVersion;

    /**
     * The base64-encoded contents of the layer archive. AWS SDK and AWS CLI clients handle the encoding for you.
     *
     * @var string|null
     */
    private $ZipFile;

    /**
     * @param array{
     *   S3Bucket?: string,
     *   S3Key?: string,
     *   S3ObjectVersion?: string,
     *   ZipFile?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->S3Bucket = $input['S3Bucket'] ?? null;
        $this->S3Key = $input['S3Key'] ?? null;
        $this->S3ObjectVersion = $input['S3ObjectVersion'] ?? null;
        $this->ZipFile = $input['ZipFile'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getS3Bucket(): ?string
    {
        return $this->S3Bucket;
    }

    public function getS3Key(): ?string
    {
        return $this->S3Key;
    }

    public function getS3ObjectVersion(): ?string
    {
        return $this->S3ObjectVersion;
    }

    public function getZipFile(): ?string
    {
        return $this->ZipFile;
    }

    public function setS3Bucket(?string $value): self
    {
        $this->S3Bucket = $value;

        return $this;
    }

    public function setS3Key(?string $value): self
    {
        $this->S3Key = $value;

        return $this;
    }

    public function setS3ObjectVersion(?string $value): self
    {
        $this->S3ObjectVersion = $value;

        return $this;
    }

    public function setZipFile(?string $value): self
    {
        $this->ZipFile = $value;

        return $this;
    }

    public function validate(): void
    {
        // There are no required properties
    }
}
