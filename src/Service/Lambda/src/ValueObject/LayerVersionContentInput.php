<?php

namespace AsyncAws\Lambda\ValueObject;

/**
 * The function layer archive.
 */
final class LayerVersionContentInput
{
    /**
     * The Amazon S3 bucket of the layer archive.
     */
    private $s3Bucket;

    /**
     * The Amazon S3 key of the layer archive.
     */
    private $s3Key;

    /**
     * For versioned objects, the version of the layer archive object to use.
     */
    private $s3ObjectVersion;

    /**
     * The base64-encoded contents of the layer archive. AWS SDK and AWS CLI clients handle the encoding for you.
     */
    private $zipFile;

    /**
     * @param array{
     *   S3Bucket?: null|string,
     *   S3Key?: null|string,
     *   S3ObjectVersion?: null|string,
     *   ZipFile?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->s3Bucket = $input['S3Bucket'] ?? null;
        $this->s3Key = $input['S3Key'] ?? null;
        $this->s3ObjectVersion = $input['S3ObjectVersion'] ?? null;
        $this->zipFile = $input['ZipFile'] ?? null;
    }

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

    public function getZipFile(): ?string
    {
        return $this->zipFile;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->s3Bucket) {
            $payload['S3Bucket'] = $v;
        }
        if (null !== $v = $this->s3Key) {
            $payload['S3Key'] = $v;
        }
        if (null !== $v = $this->s3ObjectVersion) {
            $payload['S3ObjectVersion'] = $v;
        }
        if (null !== $v = $this->zipFile) {
            $payload['ZipFile'] = base64_encode($v);
        }

        return $payload;
    }
}
