<?php

namespace AsyncAws\Lambda\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Lambda\Enum\S3ObjectStorageMode;

/**
 * A ZIP archive that contains the contents of an Lambda layer [^1]. You can specify either an Amazon S3 location, or
 * upload a layer archive directly.
 *
 * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/configuration-layers.html
 */
final class LayerVersionContentInput
{
    /**
     * The Amazon S3 bucket of the layer archive.
     *
     * @var string|null
     */
    private $s3Bucket;

    /**
     * The Amazon S3 key of the layer archive.
     *
     * @var string|null
     */
    private $s3Key;

    /**
     * For versioned objects, the version of the layer archive object to use.
     *
     * @var string|null
     */
    private $s3ObjectVersion;

    /**
     * @var S3ObjectStorageMode::*|null
     */
    private $s3ObjectStorageMode;

    /**
     * The base64-encoded contents of the layer archive. Amazon Web Services SDK and Amazon Web Services CLI clients handle
     * the encoding for you.
     *
     * @var string|null
     */
    private $zipFile;

    /**
     * @param array{
     *   S3Bucket?: string|null,
     *   S3Key?: string|null,
     *   S3ObjectVersion?: string|null,
     *   S3ObjectStorageMode?: S3ObjectStorageMode::*|null,
     *   ZipFile?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->s3Bucket = $input['S3Bucket'] ?? null;
        $this->s3Key = $input['S3Key'] ?? null;
        $this->s3ObjectVersion = $input['S3ObjectVersion'] ?? null;
        $this->s3ObjectStorageMode = $input['S3ObjectStorageMode'] ?? null;
        $this->zipFile = $input['ZipFile'] ?? null;
    }

    /**
     * @param array{
     *   S3Bucket?: string|null,
     *   S3Key?: string|null,
     *   S3ObjectVersion?: string|null,
     *   S3ObjectStorageMode?: S3ObjectStorageMode::*|null,
     *   ZipFile?: string|null,
     * }|LayerVersionContentInput $input
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

    /**
     * @return S3ObjectStorageMode::*|null
     */
    public function getS3ObjectStorageMode(): ?string
    {
        return $this->s3ObjectStorageMode;
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
        if (null !== $v = $this->s3ObjectStorageMode) {
            if (!S3ObjectStorageMode::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "S3ObjectStorageMode" for "%s". The value "%s" is not a valid "S3ObjectStorageMode".', __CLASS__, $v));
            }
            $payload['S3ObjectStorageMode'] = $v;
        }
        if (null !== $v = $this->zipFile) {
            $payload['ZipFile'] = base64_encode($v);
        }

        return $payload;
    }
}
