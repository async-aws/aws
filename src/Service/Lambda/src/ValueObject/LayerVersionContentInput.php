<?php

namespace AsyncAws\Lambda\ValueObject;

final class LayerVersionContentInput
{
    /**
     * The Amazon S3 bucket of the layer archive.
     */
    private $S3Bucket;

    /**
     * The Amazon S3 key of the layer archive.
     */
    private $S3Key;

    /**
     * For versioned objects, the version of the layer archive object to use.
     */
    private $S3ObjectVersion;

    /**
     * The base64-encoded contents of the layer archive. AWS SDK and AWS CLI clients handle the encoding for you.
     */
    private $ZipFile;

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

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->S3Bucket) {
            $payload['S3Bucket'] = $v;
        }
        if (null !== $v = $this->S3Key) {
            $payload['S3Key'] = $v;
        }
        if (null !== $v = $this->S3ObjectVersion) {
            $payload['S3ObjectVersion'] = $v;
        }
        if (null !== $v = $this->ZipFile) {
            $payload['ZipFile'] = base64_encode($v);
        }

        return $payload;
    }
}
